<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\Pendaftaran;

class DokterDashboardController extends Controller
{
    public function index()
    {
        $dokter = Auth::user();
        $now = Carbon::now();

        // Minggu dimulai Senin 00:00
        $startThisWeek = $now->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $endThisWeek   = $now->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();
        $startNextWeek = $startThisWeek->copy()->addWeek();
        $endNextWeek   = $endThisWeek->copy()->addWeek();

        /**
         * ============================================================
         * DETEKSI KOLOM DOKTER DI TABEL pendaftarans
         * ============================================================
         * Karena DB hosting kamu saat ini TIDAK punya dokter_id / diterima_oleh_dokter_id,
         * kita buat logic aman supaya tidak 500.
         */
        $kolomDokterUtama = null;
        $kolomDokterCadangan = null;

        // Prioritas sesuai yang sebelumnya kamu pakai
        if (Schema::hasColumn('pendaftarans', 'dokter_id')) {
            $kolomDokterUtama = 'dokter_id';
        }

        if (Schema::hasColumn('pendaftarans', 'diterima_oleh_dokter_id')) {
            $kolomDokterCadangan = 'diterima_oleh_dokter_id';
        }

        // Kalau project kamu ternyata pakai nama lain, tambahkan di sini:
        if (!$kolomDokterUtama && Schema::hasColumn('pendaftarans', 'id_dokter')) {
            $kolomDokterUtama = 'id_dokter';
        }
        if (!$kolomDokterUtama && Schema::hasColumn('pendaftarans', 'dokter_user_id')) {
            $kolomDokterUtama = 'dokter_user_id';
        }

        // Closure filter dokter (aman)
        $dokterFilter = function ($q) use ($dokter, $kolomDokterUtama, $kolomDokterCadangan) {
            // Kalau tidak ada kolom relasi dokter sama sekali → kembalikan data kosong, jangan error
            if (!$kolomDokterUtama && !$kolomDokterCadangan) {
                $q->whereRaw('1 = 0'); // selalu false -> hasil kosong
                return;
            }

            // Kalau ada 1 kolom saja
            if ($kolomDokterUtama && !$kolomDokterCadangan) {
                $q->where($kolomDokterUtama, $dokter->id);
                return;
            }

            if (!$kolomDokterUtama && $kolomDokterCadangan) {
                $q->where($kolomDokterCadangan, $dokter->id);
                return;
            }

            // Kalau ada dua-duanya
            $q->where(function ($qq) use ($dokter, $kolomDokterUtama, $kolomDokterCadangan) {
                $qq->where($kolomDokterUtama, $dokter->id)
                   ->orWhere($kolomDokterCadangan, $dokter->id);
            });
        };

        // ✅ Jadwal Minggu Ini
        $jadwalMingguIni = Pendaftaran::query()
            ->with('user')
            ->where($dokterFilter)
            ->whereBetween('tanggal_kunjungan', [
                $startThisWeek->toDateString(),
                $endThisWeek->toDateString(),
            ])
            ->orderBy('tanggal_kunjungan')
            ->orderBy('jam_kunjungan')
            ->get();

        // ✅ Jadwal Minggu Depan
        $jadwalMingguDepan = Pendaftaran::query()
            ->with('user')
            ->where($dokterFilter)
            ->whereBetween('tanggal_kunjungan', [
                $startNextWeek->toDateString(),
                $endNextWeek->toDateString(),
            ])
            ->orderBy('tanggal_kunjungan')
            ->orderBy('jam_kunjungan')
            ->get();

        // ✅ Total Pasien unik (berdasarkan user_id)
        $totalPasien = Pendaftaran::query()
            ->where($dokterFilter)
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        // ✅ Jadwal Hari Ini
        $totalJadwal = Pendaftaran::query()
            ->where($dokterFilter)
            ->whereDate('tanggal_kunjungan', $now->toDateString())
            ->count();

        // ✅ Total Konsultasi
        $totalKonsultasi = Pendaftaran::query()
            ->where($dokterFilter)
            ->count();

        return view('dokter.index', [
            'totalPasien' => $totalPasien,
            'totalJadwal' => $totalJadwal,
            'totalKonsultasi' => $totalKonsultasi,
            'jadwalMingguIni' => $jadwalMingguIni,
            'jadwalMingguDepan' => $jadwalMingguDepan,
        ]);
    }
}
