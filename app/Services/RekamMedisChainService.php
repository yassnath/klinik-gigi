<?php

namespace App\Services;

use App\Models\Pendaftaran;
use App\Models\RekamMedis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * RekamMedisChainService
 *
 * "Blockchain" sederhana berbasis hash-chain per pasien.
 * - Tiap record punya prev_hash -> block_hash
 * - block_hash = sha256(payload + prev_hash)
 *
 * Catatan: ini bukan blockchain publik, tapi cukup untuk kebutuhan tugas:
 * integritas data & jejak perubahan (tamper-evident).
 */
class RekamMedisChainService
{
    /**
     * Buat hash untuk 1 blok.
     */
    public function computeBlockHash(array $payload): string
    {
        // Pastikan deterministik: urutan key konsisten
        $ordered = [
            'chain_index'    => $payload['chain_index'] ?? null,
            'pasien_id'      => $payload['pasien_id'] ?? null,
            'pendaftaran_id' => $payload['pendaftaran_id'] ?? null,
            'dokter_id'      => $payload['dokter_id'] ?? null,
            'tanggal'        => $payload['tanggal'] ?? null,
            'diagnosa'       => $payload['diagnosa'] ?? null,
            'tindakan'       => $payload['tindakan'] ?? null,
            'resep'          => $payload['resep'] ?? null,
            'catatan'        => $payload['catatan'] ?? null,
            'created_at'     => $payload['created_at'] ?? null,
            'prev_hash'      => $payload['prev_hash'] ?? null,
        ];

        $json = json_encode($ordered, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return hash('sha256', (string) $json);
    }

    /**
     * Assign chain fields untuk record baru.
     * Dipanggil dari Model event (creating).
     */
    public function assignForNewRecord(RekamMedis $rm): void
    {
        // Kalau kolom belum ada, jangan bikin error di production
        if (!$this->chainColumnsAvailable()) {
            return;
        }

        // Pastikan pasien_id tersedia
        if (empty($rm->pasien_id) && !empty($rm->pendaftaran_id)) {
            $p = Pendaftaran::query()->select('id', 'user_id')->find($rm->pendaftaran_id);
            if ($p) {
                $rm->pasien_id = $p->user_id;
            }
        }

        if (empty($rm->pasien_id)) {
            // Tidak bisa bikin chain tanpa pasien_id
            return;
        }

        // Cari blok terakhir pasien ini
        $last = RekamMedis::query()
            ->where('pasien_id', $rm->pasien_id)
            ->whereNotNull('block_hash')
            ->orderByDesc('chain_index')
            ->orderByDesc('id')
            ->first();

        $rm->chain_index = $last ? ((int) $last->chain_index + 1) : 1;
        $rm->prev_hash   = $last ? (string) $last->block_hash : 'GENESIS';

        $rm->block_hash = $this->computeBlockHash([
            'chain_index'    => $rm->chain_index,
            'pasien_id'      => $rm->pasien_id,
            'pendaftaran_id' => $rm->pendaftaran_id,
            'dokter_id'      => $rm->dokter_id,
            'tanggal'        => $rm->tanggal,
            'diagnosa'       => $rm->diagnosa,
            'tindakan'       => $rm->tindakan,
            'resep'          => $rm->resep,
            'catatan'        => $rm->catatan,
            'created_at'     => $rm->created_at ? $rm->created_at->toISOString() : null,
            'prev_hash'      => $rm->prev_hash,
        ]);
    }

    /**
     * Verifikasi chain untuk 1 pasien.
     * Return array ringkas untuk ditampilkan di UI.
     */
    public function verifyForPasien(int $pasienId): array
    {
        if (!$this->chainColumnsAvailable()) {
            return [
                'available' => false,
                'valid' => null,
                'total' => 0,
                'invalid_count' => 0,
                'details' => [],
                'message' => 'Kolom blockchain belum tersedia (migration belum dijalankan).',
            ];
        }

        $rows = RekamMedis::query()
            ->where('pasien_id', $pasienId)
            ->orderBy('chain_index')
            ->orderBy('id')
            ->get();

        $details = [];
        $prevExpected = 'GENESIS';
        $invalid = 0;

        foreach ($rows as $rm) {
            $expectedHash = $this->computeBlockHash([
                'chain_index'    => $rm->chain_index,
                'pasien_id'      => $rm->pasien_id,
                'pendaftaran_id' => $rm->pendaftaran_id,
                'dokter_id'      => $rm->dokter_id,
                'tanggal'        => $rm->tanggal,
                'diagnosa'       => $rm->diagnosa,
                'tindakan'       => $rm->tindakan,
                'resep'          => $rm->resep,
                'catatan'        => $rm->catatan,
                'created_at'     => optional($rm->created_at)->toISOString(),
                'prev_hash'      => $rm->prev_hash,
            ]);

            $prevOk = ((string) $rm->prev_hash === (string) $prevExpected);
            $hashOk = ((string) $rm->block_hash === (string) $expectedHash);
            $ok = $prevOk && $hashOk;

            if (!$ok) {
                $invalid++;
            }

            $details[] = [
                'id' => $rm->id,
                'chain_index' => (int) $rm->chain_index,
                'prev_hash' => (string) $rm->prev_hash,
                'prev_expected' => (string) $prevExpected,
                'block_hash' => (string) $rm->block_hash,
                'hash_expected' => (string) $expectedHash,
                'ok' => $ok,
            ];

            $prevExpected = (string) $rm->block_hash;
        }

        return [
            'available' => true,
            'valid' => ($invalid === 0),
            'total' => $rows->count(),
            'invalid_count' => $invalid,
            'details' => $details,
            'message' => null,
        ];
    }

    /**
     * Backfill chain untuk data lama (best effort).
     * Dipakai di migration (try/catch), atau bisa dipanggil manual.
     */
    public function backfillAll(): void
    {
        if (!$this->chainColumnsAvailable()) {
            return;
        }

        // Kelompokkan per pasien
        $pasienIds = RekamMedis::query()
            ->select('pasien_id')
            ->whereNotNull('pasien_id')
            ->distinct()
            ->pluck('pasien_id');

        foreach ($pasienIds as $pid) {
            $this->backfillForPasien((int) $pid);
        }
    }

    public function backfillForPasien(int $pasienId): void
    {
        if (!$this->chainColumnsAvailable()) {
            return;
        }

        DB::transaction(function () use ($pasienId) {
            $rows = RekamMedis::query()
                ->where('pasien_id', $pasienId)
                ->orderBy('created_at')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $prev = 'GENESIS';
            $idx = 1;

            foreach ($rows as $rm) {
                $rm->chain_index = $idx;
                $rm->prev_hash = $prev;
                $rm->block_hash = $this->computeBlockHash([
                    'chain_index'    => $rm->chain_index,
                    'pasien_id'      => $rm->pasien_id,
                    'pendaftaran_id' => $rm->pendaftaran_id,
                    'dokter_id'      => $rm->dokter_id,
                    'tanggal'        => $rm->tanggal,
                    'diagnosa'       => $rm->diagnosa,
                    'tindakan'       => $rm->tindakan,
                    'resep'          => $rm->resep,
                    'catatan'        => $rm->catatan,
                    'created_at'     => optional($rm->created_at)->toISOString(),
                    'prev_hash'      => $rm->prev_hash,
                ]);

                $rm->save();

                $prev = (string) $rm->block_hash;
                $idx++;
            }
        });
    }

    private function chainColumnsAvailable(): bool
    {
        try {
            return Schema::hasColumn('rekam_medis', 'block_hash')
                && Schema::hasColumn('rekam_medis', 'prev_hash')
                && Schema::hasColumn('rekam_medis', 'chain_index')
                && Schema::hasColumn('rekam_medis', 'pasien_id');
        } catch (\Throwable $e) {
            Log::warning('Schema check failed (chainColumnsAvailable)', ['e' => $e->getMessage()]);
            return false;
        }
    }
}
