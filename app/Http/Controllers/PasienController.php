<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Services\RekamMedisChainService;

class PasienController extends Controller
{
    public function __construct()
    {
        // Semua endpoint butuh login, KECUALI scan QR (public)
        $this->middleware('auth')->except(['scan']);
    }

    /**
     * Kartu pasien (role pasien)
     * QR harus IMG PNG (bukan SVG).
     */
    public function kartu()
    {
        $user = Auth::user();

        if (($user->role ?? '') !== 'pasien') {
            abort(403, 'Hanya pasien yang dapat melihat kartu pasien.');
        }

        $hasNoRm    = Schema::hasColumn('users', 'no_rm');
        $hasQrToken = Schema::hasColumn('users', 'qr_token');
        $hasQrPath  = Schema::hasColumn('users', 'qr_path');

        // Generate No RM kalau kosong (kalau kolomnya ada)
        if ($hasNoRm && empty($user->no_rm)) {
            $user->no_rm = 'RM-' . now()->format('Ym') . '-' . str_pad((string) $user->id, 5, '0', STR_PAD_LEFT);
        }

        // Token QR
        $qrToken = $user->qr_token ?? null;
        if ($hasQrToken) {
            if (empty($user->qr_token)) {
                $user->qr_token = (string) Str::uuid();
            }
            $qrToken = $user->qr_token;
        } else {
            // Fallback token sementara agar halaman tidak 500
            $qrToken = $qrToken ?: (string) Str::uuid();
        }

        // Link scan untuk publik (tanpa login)
        $scanUrl = route('pasien.scan', $qrToken);

        // Prefer: simpan file PNG ke public/patient_qr (kalau bisa write)
        // Fallback: data URI (tetap IMG PNG) kalau public tidak bisa ditulis
        $folder = 'patient_qr';
        $pngRelativePath = "{$folder}/{$qrToken}.png";
        $pngAbsolutePath = public_path($pngRelativePath);

        $qrDataUri = null;

        $needGenerate = true;
        if ($hasQrPath && !empty($user->qr_path) && file_exists(public_path($user->qr_path))) {
            $needGenerate = false;
            $pngRelativePath = $user->qr_path;
        } elseif (file_exists($pngAbsolutePath)) {
            $needGenerate = false;
        }

        if ($needGenerate) {
            try {
                $dir = public_path($folder);
                if (!is_dir($dir)) {
                    if (!@mkdir($dir, 0755, true) && !is_dir($dir)) {
                        throw new \RuntimeException("Gagal membuat folder: {$dir}");
                    }
                }

                $png = QrCode::format('png')->size(250)->margin(1)->generate($scanUrl);
                $written = @file_put_contents($pngAbsolutePath, $png);

                if ($written === false) {
                    $qrDataUri = 'data:image/png;base64,' . base64_encode($png);
                } else {
                    if ($hasQrPath) {
                        $user->qr_path = $pngRelativePath;
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('QR file write failed (kartu)', ['e' => $e->getMessage()]);
                try {
                    $png = QrCode::format('png')->size(250)->margin(1)->generate($scanUrl);
                    $qrDataUri = 'data:image/png;base64,' . base64_encode($png);
                } catch (\Throwable $e2) {
                    Log::error('QR generate failed (kartu)', ['e' => $e2->getMessage()]);
                    $qrDataUri = null;
                }
            }
        }

        $qrUrl = $qrDataUri ? $qrDataUri : asset($pngRelativePath);

        // Save user hanya kalau kolom memang ada (hindari SQL error)
        if ($hasNoRm || $hasQrToken || $hasQrPath) {
            try {
                $user->save();
            } catch (\Throwable $e) {
                Log::warning('User save failed (kartu)', ['e' => $e->getMessage()]);
            }
        }

        return view('pasien.kartu', compact('user', 'qrUrl'));
    }

    /**
     * Endpoint QR image PNG (untuk <img src="...">), TANPA SVG.
     * Route: /qr/pasien/{token}
     */
    public function qrImage(string $token)
    {
        $viewer = Auth::user();
        if (!in_array(($viewer->role ?? ''), ['pasien', 'resepsionis', 'dokter', 'admin'], true)) {
            abort(403, 'Tidak berwenang mengakses QR.');
        }

        if (!Schema::hasColumn('users', 'qr_token')) {
            abort(404, 'Fitur QR belum aktif di server. Jalankan migration (add_patient_qr_to_users_table).');
        }

        $pasien = User::where('qr_token', $token)->first();
        if (!$pasien) {
            abort(404, 'QR tidak valid.');
        }

        if (Schema::hasColumn('users', 'qr_path') && !empty($pasien->qr_path)) {
            $path = public_path($pasien->qr_path);
            if (is_file($path)) {
                return response()->file($path, [
                    'Content-Type'  => 'image/png',
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }
        }

        $scanUrl = route('pasien.scan', $token);
        $png = QrCode::format('png')->size(250)->margin(1)->generate($scanUrl);

        return response($png, 200, [
            'Content-Type'  => 'image/png',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * ✅ PUBLIC: Scan QR pasien (tanpa login)
     * Route: /scan/pasien/{token}
     *
     * Menampilkan rekam medis read-only + verifikasi integritas hash-chain.
     */
    public function scan(string $token)
    {
        // Kalau kolom qr_token belum ada, jangan 500
        if (!Schema::hasColumn('users', 'qr_token')) {
            abort(404, 'Fitur QR belum aktif di server. Jalankan migration (add_patient_qr_to_users_table).');
        }

        // Temukan pasien dari token QR
        $pasien = User::where('qr_token', $token)->first();

        if (!$pasien) {
            abort(404, 'QR pasien tidak valid / pasien tidak ditemukan.');
        }

        // QR URL (opsional) untuk ditampilkan
        $pasien->qr_url = route('pasien.qr.image', $token);

        // Riwayat pendaftaran pasien
        $pendaftarans = Pendaftaran::where('user_id', $pasien->id)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        // Rekam medis pasien
        $rekamMedisList = RekamMedis::with(['dokter', 'pendaftaran'])
            ->whereHas('pendaftaran', function ($q) use ($pasien) {
                $q->where('user_id', $pasien->id);
            })
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        // Verifikasi "blockchain" (hash-chain) untuk rekam medis pasien
        $chain = null;
        try {
            $service = app(RekamMedisChainService::class);
            $chain = $service->verifyForPasien((int) $pasien->id);
        } catch (\Throwable $e) {
            Log::warning('Chain verify failed (scan)', ['e' => $e->getMessage()]);
        }

        return view('resepsionis.scan_pasien', compact('pasien', 'pendaftarans', 'rekamMedisList', 'chain'));
    }
}
