<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalDokter;
use App\Models\User;
use App\Models\Pendaftaran;

class DokterJadwalController extends Controller
{
    public function index()
    {
        $jadwals = JadwalDokter::where('dokter_id', Auth::id())->get();
        return view('dokter.manajemen_jadwal', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        JadwalDokter::create([
            'dokter_id' => Auth::id(),
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('dokter.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $jadwal = JadwalDokter::findOrFail($id);

        if ($jadwal->dokter_id != Auth::id()) {
            abort(403);
        }

        $jadwal->delete();

        return redirect()->route('dokter.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Menampilkan jadwal dokter di sisi pasien + form daftar konsultasi di bawah tabel.
     * Route: biasanya /jadwal-dokter
     */
    public function pasienView(Request $request)
    {
        // Tabel jadwal dokter
        $jadwals = JadwalDokter::with('dokter')->get();

        // Data untuk form daftar konsultasi (copy logic dari PendaftaranController@create)
        $selectedTanggal   = (string) $request->query('tanggal_kunjungan', '');
        $selectedSpesialis = (string) $request->query('spesialis', '');

        $spesialisList = User::query()
            ->where('role', 'dokter')
            ->whereNotNull('spesialis')
            ->where('spesialis', '!=', '')
            ->select('spesialis')
            ->distinct()
            ->orderBy('spesialis')
            ->pluck('spesialis');

        $dokters = collect();
        $jadwalsForTanggal = collect();

        if ($selectedTanggal !== '' && $selectedSpesialis !== '') {
            $hari = \Carbon\Carbon::parse($selectedTanggal)->locale('id')->translatedFormat('l');

            $candidateDokters = User::query()
                ->where('role', 'dokter')
                ->where('spesialis', $selectedSpesialis)
                ->orderBy('name')
                ->get();

            $jadwalsForTanggal = JadwalDokter::query()
                ->whereIn('dokter_id', $candidateDokters->pluck('id'))
                ->where('hari', $hari)
                ->get();

            $dokters = $candidateDokters->filter(function ($dokter) use ($selectedTanggal) {
                $count = Pendaftaran::countForDoctorOnDate((int) $dokter->id, (string) $selectedTanggal);
                return $count < 5;
            })->values();
        }

        return view('pasien.jadwal_dokter', [
            'jadwals'            => $jadwals,
            'selectedTanggal'    => $selectedTanggal,
            'selectedSpesialis'  => $selectedSpesialis,
            'spesialisList'      => $spesialisList,
            'dokters'            => $dokters,
            'jadwalsForTanggal'  => $jadwalsForTanggal,
        ]);
    }
}
