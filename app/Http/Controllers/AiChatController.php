<?php

namespace App\Http\Controllers;

use App\Models\JadwalDokter;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    public function reply(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = trim($data['message']);

        $apiKey = config('services.cerebras.key');
        $model = config('services.cerebras.model', 'llama3.1-8b');
        $baseUrl = rtrim(config('services.cerebras.base_url', 'https://api.cerebras.ai/v1'), '/');

        if (!$apiKey) {
            return response()->json([
                'error' => 'Cerebras API key belum di-set. Tambahkan CEREBRAS_API_KEY di file .env (server-side).'
            ], 500);
        }

        // --- Klinik system prompt (server-side) ---
        $system = (string) config('clinic_chatbot.system_prompt', '');
        if (trim($system) === '') {
            $system = 'Kamu adalah AI Customer Service. Jawab dalam Bahasa Indonesia.';
        }

        $contextPayload = $this->buildPublicContext();
        $contextJson = json_encode($contextPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($contextJson === false) {
            $contextJson = '';
        }

        $messages = [
            ['role' => 'system', 'content' => $system],
        ];
        if ($contextJson !== '') {
            $messages[] = ['role' => 'system', 'content' => "DATA TERKINI (NON-PRIBADI):\n" . $contextJson];
        }
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        try {
            $resp = Http::retry(2, 250)
                ->timeout(20)
                ->withToken($apiKey)
                ->acceptJson()
                ->post("{$baseUrl}/chat/completions", [
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => 0.4,
                    'max_tokens' => 350,
                ]);

            if (!$resp->successful()) {
                $body = $resp->json();
                $msg = $body['error']['message'] ?? $body['error'] ?? $resp->body();

                return response()->json([
                    'error' => "Cerebras error ({$resp->status()}): " . (is_string($msg) ? $msg : json_encode($msg)),
                ], 500);
            }

            $reply = $resp->json('choices.0.message.content');

            return response()->json([
                'reply' => is_string($reply) && trim($reply) !== ''
                    ? trim($reply)
                    : 'Maaf, aku belum bisa menjawab itu. Boleh jelaskan lebih detail?'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Gagal menghubungi Cerebras: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function buildPublicContext(): array
    {
        $maxQuota = (int) config('clinic_chatbot.max_quota_per_doctor', 5);

        $doctors = User::query()
            ->where('role', 'dokter')
            ->orderBy('name')
            ->get(['id', 'name', 'spesialis']);

        $doctorMap = $doctors->keyBy('id');
        $doctorIds = $doctorMap->keys()->all();

        $schedules = $doctorIds
            ? JadwalDokter::query()
                ->whereIn('dokter_id', $doctorIds)
                ->orderBy('dokter_id')
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get(['dokter_id', 'hari', 'jam_mulai', 'jam_selesai'])
            : collect();

        $scheduleList = $schedules->map(function ($schedule) use ($doctorMap) {
            $doctor = $doctorMap->get($schedule->dokter_id);

            return [
                'dokter' => $doctor ? $doctor->name : 'Tidak diketahui',
                'spesialis' => $doctor ? ($doctor->spesialis ?: null) : null,
                'hari' => $schedule->hari,
                'jam_mulai' => $this->formatTime($schedule->jam_mulai),
                'jam_selesai' => $this->formatTime($schedule->jam_selesai),
            ];
        })->values();

        return [
            'dokter' => $doctors->map(function ($doctor) {
                return [
                    'nama' => $doctor->name,
                    'spesialis' => $doctor->spesialis ?: null,
                ];
            })->values(),
            'jadwal_dokter' => $scheduleList,
            'ketersediaan_7_hari' => $this->buildAvailability($doctorMap, $schedules, $maxQuota),
            'biaya_layanan' => config('clinic_chatbot.service_fees', []),
            'catatan_biaya' => (string) config('clinic_chatbot.service_fees_note', ''),
            'catatan_ketersediaan' => 'Ketersediaan dihitung dari kuota ' . $maxQuota . ' pasien per dokter per tanggal.',
        ];
    }

    private function buildAvailability($doctorMap, $schedules, int $maxQuota): array
    {
        if ($doctorMap->isEmpty() || $schedules->isEmpty()) {
            return [];
        }

        $startDate = now()->startOfDay();
        $endDate = $startDate->copy()->addDays(6);

        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dates[] = [
                'tanggal' => $date->toDateString(),
                'hari' => $date->locale('id')->translatedFormat('l'),
            ];
        }

        $counts = Pendaftaran::query()
            ->selectRaw('dokter_id, DATE(tanggal_kunjungan) as tanggal, COUNT(*) as total')
            ->whereBetween('tanggal_kunjungan', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereNotIn('status', ['ditolak'])
            ->groupBy('dokter_id', 'tanggal')
            ->get();

        $countMap = [];
        foreach ($counts as $row) {
            $key = $row->dokter_id . '|' . $row->tanggal;
            $countMap[$key] = (int) $row->total;
        }

        $availability = [];
        foreach ($dates as $dateInfo) {
            $availability[$dateInfo['tanggal']] = [
                'tanggal' => $dateInfo['tanggal'],
                'hari' => $dateInfo['hari'],
                'dokter_ready' => [],
            ];
        }

        foreach ($schedules as $schedule) {
            $doctor = $doctorMap->get($schedule->dokter_id);
            if (!$doctor) {
                continue;
            }

            foreach ($dates as $dateInfo) {
                if ($schedule->hari !== $dateInfo['hari']) {
                    continue;
                }

                $key = $schedule->dokter_id . '|' . $dateInfo['tanggal'];
                $count = $countMap[$key] ?? 0;
                $remaining = $maxQuota - $count;
                if ($remaining <= 0) {
                    continue;
                }

                $availability[$dateInfo['tanggal']]['dokter_ready'][] = [
                    'dokter' => $doctor->name,
                    'spesialis' => $doctor->spesialis ?: null,
                    'jam_mulai' => $this->formatTime($schedule->jam_mulai),
                    'jam_selesai' => $this->formatTime($schedule->jam_selesai),
                    'sisa_kuota' => $remaining,
                    'kuota_maks' => $maxQuota,
                ];
            }
        }

        return array_values(array_filter($availability, function ($day) {
            return count($day['dokter_ready']) > 0;
        }));
    }

    private function formatTime($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $text = (string) $value;
        return strlen($text) >= 5 ? substr($text, 0, 5) : $text;
    }
}
