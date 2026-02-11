<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ResepsionisDashboardController extends Controller
{
    /**
     * =========================
     * DASHBOARD RESEPSIONIS
     * =========================
     */
    public function index()
    {
        $today = now()->toDateString();

        // Total seluruh pasien
        $totalPasien = User::where('role', 'pasien')->count();

        // Pendaftaran baru hari ini (menunggu)
        $pendaftaranBaru = Pendaftaran::whereDate('created_at', $today)
            ->where('status', 'menunggu')
            ->count();

        // Dokter aktif
        $dokterAktif = User::where('role', 'dokter')->count();

        // Jadwal hari ini (aman kalau tabel tidak ada)
        $jadwalHariIni = 0;
        try {
            $jadwalHariIni = DB::table('jadwal_dokters')
                ->whereDate('tanggal', $today)
                ->count();
        } catch (\Throwable $e) {
            $jadwalHariIni = 0;
        }

        return view('resepsionis.index', compact(
            'totalPasien',
            'pendaftaranBaru',
            'dokterAktif',
            'jadwalHariIni'
        ));
    }

    /**
     * =========================
     * PASIEN AKTIF (HARI INI s/d 7 HARI KE DEPAN)
     * =========================
     */
    public function pasienAktif(Request $request)
    {
        $user = auth()->user();
        if (($user->role ?? '') !== 'resepsionis' && ($user->role ?? '') !== 'admin') {
            abort(403);
        }

        // Default filter
        $from = $request->query('from')
            ? Carbon::parse($request->query('from'))->startOfDay()
            : Carbon::now()->startOfDay();

        $to = $request->query('to')
            ? Carbon::parse($request->query('to'))->endOfDay()
            : Carbon::now()->addDays(7)->endOfDay();

        $pasienAktif = Pendaftaran::with(['user', 'dokter'])
            ->whereBetween('tanggal_kunjungan', [
                $from->toDateString(),
                $to->toDateString()
            ])
            ->orderBy('tanggal_kunjungan', 'asc')
            ->orderBy('jam_kunjungan', 'asc')
            ->limit(200)
            ->get();

        return view('resepsionis.pasien_aktif', compact(
            'pasienAktif',
            'from',
            'to'
        ));
    }

    /**
     * =========================
     * KONFIRMASI: TERIMA (dari menunggu -> diterima)
     * =========================
     */
    public function terima(Pendaftaran $pendaftaran)
    {
        if (!in_array(auth()->user()->role ?? '', ['resepsionis', 'admin'])) {
            abort(403);
        }

        $status = strtolower((string) ($pendaftaran->status ?? ''));

        // hanya boleh terima jika masih menunggu
        if (!in_array($status, ['menunggu_konfirmasi', 'menunggu konfirmasi', 'menunggu', 'pending', 'baru'])) {
            return back()->with('error', 'Hanya pendaftaran yang masih menunggu yang bisa diterima.');
        }

        $pendaftaran->update([
            'status' => 'diterima',
        ]);

        return back()->with('success', 'Pendaftaran berhasil diterima.');
    }

    /**
     * =========================
     * KONFIRMASI: TOLAK (dari menunggu -> ditolak)
     * =========================
     */
    public function tolak(Pendaftaran $pendaftaran)
    {
        if (!in_array(auth()->user()->role ?? '', ['resepsionis', 'admin'])) {
            abort(403);
        }

        $status = strtolower((string) ($pendaftaran->status ?? ''));

        // hanya boleh tolak jika masih menunggu
        if (!in_array($status, ['menunggu_konfirmasi', 'menunggu konfirmasi', 'menunggu', 'pending', 'baru'])) {
            return back()->with('error', 'Hanya pendaftaran yang masih menunggu yang bisa ditolak.');
        }

        $pendaftaran->update([
            'status' => 'ditolak',
        ]);

        return back()->with('success', 'Pendaftaran berhasil ditolak.');
    }

    /**
     * =========================
     * CHECK-IN PASIEN (HADIR)
     * =========================
     */
    public function checkin(Pendaftaran $pendaftaran)
    {
        if (!in_array(auth()->user()->role ?? '', ['resepsionis', 'admin'])) {
            abort(403);
        }

        $status = strtolower((string) ($pendaftaran->status ?? ''));

        // Hanya bisa check-in jika diterima
        if ($status !== 'diterima') {
            return back()->with('error', 'Check-in hanya bisa untuk pasien dengan status diterima.');
        }

        if ($pendaftaran->checked_in_at !== null) {
            return back()->with('error', 'Pasien sudah di-check-in.');
        }

        if ($pendaftaran->no_show_at !== null) {
            return back()->with('error', 'Pasien sudah ditandai Tidak Hadir.');
        }

        $pendaftaran->update([
            'status'         => 'hadir',
            'checked_in_at'  => now(),
            'checked_in_by'  => auth()->id(),
        ]);

        return back()->with('success', 'Pasien berhasil di-check-in (Hadir).');
    }

    /**
     * =========================
     * PASIEN TIDAK HADIR (NO-SHOW)
     * =========================
     */
    public function noShow(Pendaftaran $pendaftaran)
    {
        if (!in_array(auth()->user()->role ?? '', ['resepsionis', 'admin'])) {
            abort(403);
        }

        $status = strtolower((string) ($pendaftaran->status ?? ''));

        // Hanya bisa no-show jika diterima
        if ($status !== 'diterima') {
            return back()->with('error', 'Tidak Hadir hanya bisa untuk pasien dengan status diterima.');
        }

        if ($pendaftaran->checked_in_at !== null) {
            return back()->with('error', 'Pasien sudah hadir, tidak bisa ditandai Tidak Hadir.');
        }

        if ($pendaftaran->no_show_at !== null) {
            return back()->with('error', 'Pasien sudah ditandai Tidak Hadir.');
        }

        $pendaftaran->update([
            'status'       => 'tidak_hadir',
            'no_show_at'   => now(),
            'no_show_by'   => auth()->id(),
        ]);

        return back()->with('success', 'Pasien berhasil ditandai Tidak Hadir (No-show).');
    }
}
