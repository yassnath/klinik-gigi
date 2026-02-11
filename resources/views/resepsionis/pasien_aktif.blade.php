@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-4">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-4">
        <div>
            <h1 class="text-3xl font-extrabold text-blue-700">👥 Pasien Aktif</h1>
            <p class="text-sm text-gray-600 mt-1">
                Menampilkan daftar konsultasi pasien (hari ini s/d 7 hari ke depan). Kamu bisa ubah pakai filter tanggal.
            </p>
        </div>

        {{-- Filter tanggal --}}
        <form method="GET" class="flex flex-col sm:flex-row gap-2">
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600">Dari</label>
                <input type="date" name="from"
                       value="{{ isset($from) ? $from->toDateString() : '' }}"
                       class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600">Sampai</label>
                <input type="date" name="to"
                       value="{{ isset($to) ? $to->toDateString() : '' }}"
                       class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md text-sm">
                Filter
            </button>
        </form>
    </div>

    {{-- Info range --}}
    <div class="mb-3 text-sm text-gray-600">
        Range:
        <span class="font-semibold text-gray-800">{{ isset($from) ? $from->toDateString() : '-' }}</span>
        s/d
        <span class="font-semibold text-gray-800">{{ isset($to) ? $to->toDateString() : '-' }}</span>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-3 p-3 rounded-lg bg-green-50 text-green-700 text-sm border border-green-100">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-3 p-3 rounded-lg bg-red-50 text-red-700 text-sm border border-red-100">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-lg shadow p-4">
        <div class="overflow-x-auto">
            <table class="min-w-[1400px] w-full text-sm">
                <thead class="bg-blue-50 text-blue-800">
                    <tr>
                        <th class="px-3 py-2 text-left">No</th>
                        <th class="px-3 py-2 text-left">Nama Pasien</th>
                        <th class="px-3 py-2 text-left">Tanggal Kunjungan</th>
                        <th class="px-3 py-2 text-left">Diterima Oleh</th>
                        <th class="px-3 py-2 text-left">No Antrian</th>
                        <th class="px-3 py-2 text-left">Dokter</th>
                        <th class="px-3 py-2 text-left">Spesialis</th>
                        <th class="px-3 py-2 text-left">Keluhan</th>
                        <th class="px-3 py-2 text-left">Status</th>
                        <th class="px-3 py-2 text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse(($pasienAktif ?? []) as $i => $p)
                        @php
                            $rawStatus = (string) ($p->status ?? 'menunggu_konfirmasi');
                            $statusLower = strtolower($rawStatus);

                            $isMenunggu = in_array($statusLower, [
                                'menunggu_konfirmasi', 'menunggu konfirmasi', 'menunggu', 'pending', 'baru'
                            ]);
                            $isDiterima = in_array($statusLower, ['diterima', 'disetujui']);
                            $isHadir = in_array($statusLower, ['hadir', 'checkin', 'check-in']);
                            $isNoShow = in_array($statusLower, ['tidak_hadir', 'tidak hadir', 'no-show', 'noshow']);

                            $badge = 'bg-gray-100 text-gray-700';
                            if ($isMenunggu) $badge = 'bg-yellow-100 text-yellow-700';
                            elseif ($isDiterima) $badge = 'bg-blue-100 text-blue-700';
                            elseif ($isHadir) $badge = 'bg-green-100 text-green-700';
                            elseif ($isNoShow) $badge = 'bg-red-100 text-red-700';

                            // SAFE relation
                            $namaPasien = optional($p->user)->name ?? ($p->nama ?? '-');
                            $namaDokter = optional($p->dokter)->name ?? '-';
                            $spesialis  = optional($p->dokter)->spesialis ?? ($p->spesialis ?? '-');

                            // SAFE route check (biar tidak 500 kalau route belum ada)
                            $hasTerima  = \Illuminate\Support\Facades\Route::has('resepsionis.pendaftaran.terima');
                            $hasTolak   = \Illuminate\Support\Facades\Route::has('resepsionis.pendaftaran.tolak');
                            $hasCheckin = \Illuminate\Support\Facades\Route::has('resepsionis.pendaftaran.checkin');
                            $hasNoShow  = \Illuminate\Support\Facades\Route::has('resepsionis.pendaftaran.no_show');
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2">{{ $i + 1 }}</td>

                            <td class="px-3 py-2 font-semibold text-gray-800 whitespace-nowrap">
                                {{ $namaPasien }}
                            </td>

                           <td class="px-3 py-2 whitespace-nowrap">
    @php
        $tgl = $p->tanggal_kunjungan ?? null;
        $jam = $p->jam_kunjungan ?? null;

        // Output default kalau kosong
        $tanggalFormatted = $tgl ? \Carbon\Carbon::parse($tgl)->format('Y-m-d') : '-';
        $jamFormatted = $jam ? \Carbon\Carbon::parse($jam)->format('H:i') : '';
    @endphp

    {{ trim($tanggalFormatted . ' ' . $jamFormatted) }}
</td>

                            <td class="px-3 py-2 whitespace-nowrap">
                                {{ $namaDokter }}
                            </td>

                            <td class="px-3 py-2 whitespace-nowrap">
                                {{ $p->no_antrian ?? $p->kode_antrian ?? '-' }}
                            </td>

                            <td class="px-3 py-2 whitespace-nowrap">
                                {{ $namaDokter }}
                            </td>

                            <td class="px-3 py-2 whitespace-nowrap">
                                {{ $spesialis }}
                            </td>

                            <td class="px-3 py-2 text-gray-700 min-w-[260px]">
                                {{ $p->keluhan ?? '-' }}
                            </td>

                            <td class="px-3 py-2 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                    {{ $rawStatus }}
                                </span>
                            </td>

                            <td class="px-3 py-2 whitespace-nowrap">
                                @if($isMenunggu && $hasTerima && $hasTolak)
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <form method="POST" action="{{ route('resepsionis.pendaftaran.terima', $p->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="w-full sm:w-auto px-3 py-1.5 rounded-lg text-white bg-blue-600 hover:bg-blue-700 text-xs font-semibold">
                                                Terima
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('resepsionis.pendaftaran.tolak', $p->id) }}"
                                              onsubmit="return confirm('Yakin tolak pendaftaran ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="w-full sm:w-auto px-3 py-1.5 rounded-lg text-white bg-gray-700 hover:bg-gray-800 text-xs font-semibold">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>

                                @elseif($isDiterima && $hasCheckin && $hasNoShow)
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <form method="POST" action="{{ route('resepsionis.pendaftaran.checkin', $p->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="w-full sm:w-auto px-3 py-1.5 rounded-lg text-white bg-green-600 hover:bg-green-700 text-xs font-semibold">
                                                Check-in
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('resepsionis.pendaftaran.no_show', $p->id) }}"
                                              onsubmit="return confirm('Yakin tandai pasien TIDAK HADIR (No-show)?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="w-full sm:w-auto px-3 py-1.5 rounded-lg text-white bg-red-600 hover:bg-red-700 text-xs font-semibold">
                                                Tidak Hadir
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-3 py-8 text-center text-gray-500">
                                Tidak ada data pasien aktif pada rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 text-xs text-gray-500">
            *Tabel bisa di-scroll ke samping di HP.
        </div>
    </div>
</div>
@endsection
