@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-blue-600">Jadwal Dokter</h1>

    {{-- TABEL JADWAL DOKTER --}}
    <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-blue-100 text-blue-800 text-center">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Dokter</th>
                    <th class="px-4 py-2">Spesialis</th>
                    <th class="px-4 py-2">Hari</th>
                    <th class="px-4 py-2">Jam Praktik</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-center">
                @forelse ($jadwals as $index => $jadwal)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">
                            {{ ($jadwal->dokter && ($jadwal->dokter->role ?? null) === 'dokter') ? $jadwal->dokter->name : 'Nama Dokter' }}
                        </td>
                        <td class="px-4 py-2">
                            {{ ($jadwal->dokter && ($jadwal->dokter->role ?? null) === 'dokter') ? ($jadwal->dokter->spesialis ?? '-') : '-' }}
                        </td>
                        <td class="px-4 py-2">{{ ucfirst($jadwal->hari) }}</td>
                        <td class="px-4 py-2">
                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada jadwal dokter yang tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- FORM DAFTAR KONSULTASI (DI BAWAH TABEL) --}}
    <div class="mt-10 max-w-6xl mx-auto px-2">
        <h2 class="text-3xl font-extrabold text-blue-800 mb-4 text-center">📝 Daftar Konsultasi Pasien</h2>

        <div class="bg-white shadow-xl rounded-lg p-6 border border-gray-200">
            {{-- Ringkasan error --}}
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                    <div class="font-semibold mb-1">Terjadi kesalahan pada input Anda:</div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $u = Auth::user();
                $prefNama   = old('nama', $u->name ?? '');
                $prefTgl    = old('tanggal_lahir', optional($u->tanggal_lahir)->format('Y-m-d') ?? '');
                $prefJk     = old('jenis_kelamin', $u->jenis_kelamin ?? '');
                $prefHp     = old('no_hp', $u->no_hp ?? $u->telepon ?? '');
                $prefNik    = old('nik', $u->nik ?? '');
                $prefAlamat = old('alamat', $u->alamat ?? '');
            @endphp

            <form action="{{ route('pendaftaran.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>

                        <input type="hidden" name="nama" value="{{ $prefNama }}">
                        <input type="text" id="nama" value="{{ $prefNama }}" readonly
                               class="w-full border border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed focus:outline-none rounded-lg px-4 py-2 text-sm"
                               placeholder="Contoh: Ahmad Rizki">
                        @error('nama')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir</label>

                        <input type="hidden" name="tanggal_lahir" value="{{ $prefTgl }}">
                        <input type="date" id="tanggal_lahir" value="{{ $prefTgl }}" readonly
                               class="w-full border border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed focus:outline-none rounded-lg px-4 py-2 text-sm">
                        @error('tanggal_lahir')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-1">Jenis Kelamin</label>

                        <input type="hidden" name="jenis_kelamin" value="{{ $prefJk }}">
                        <input type="text" id="jenis_kelamin" value="{{ $prefJk }}" readonly
                               class="w-full border border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed focus:outline-none rounded-lg px-4 py-2 text-sm"
                               placeholder="-">
                        @error('jenis_kelamin')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No HP -->
                    <div>
                        <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-1">Nomor HP</label>

                        <input type="hidden" name="no_hp" value="{{ $prefHp }}">
                        <input type="text" id="no_hp" value="{{ $prefHp }}" readonly
                               class="w-full border border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed focus:outline-none rounded-lg px-4 py-2 text-sm"
                               placeholder="08xxxxxxxxxx">
                        @error('no_hp')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label for="nik" class="block text-sm font-semibold text-gray-700 mb-1">NIK</label>

                        <input type="hidden" name="nik" value="{{ $prefNik }}">
                        <input type="text" id="nik" value="{{ $prefNik }}" readonly
                               class="w-full border border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed focus:outline-none rounded-lg px-4 py-2 text-sm"
                               placeholder="16 digit NIK">
                        @error('nik')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>

                        <input type="hidden" name="alamat" value="{{ $prefAlamat }}">
                        <input type="text" id="alamat" value="{{ $prefAlamat }}" readonly
                               class="w-full border border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed focus:outline-none rounded-lg px-4 py-2 text-sm"
                               placeholder="Alamat pasien">
                        @error('alamat')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                   
                    <!-- Tanggal Kunjungan -->
                    <div>
                        <label for="tanggal_kunjungan" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Kunjungan</label>
                        <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan"
                               value="{{ old('tanggal_kunjungan', $selectedTanggal ?? '') }}"
                               class="w-full border @error('tanggal_kunjungan') border-red-500 @else border-gray-300 @enderror focus:ring focus:ring-blue-200 focus:outline-none rounded-lg px-4 py-2 text-sm"
                               required>
                        @error('tanggal_kunjungan')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Spesialis -->
                    <div>
                        <label for="spesialis" class="block text-sm font-semibold text-gray-700 mb-1">Spesialis Dokter</label>
                        <select id="spesialis" name="spesialis"
                                class="w-full border @error('spesialis') border-red-500 @else border-gray-300 @enderror focus:ring focus:ring-blue-200 focus:outline-none rounded-lg px-4 py-2 text-sm"
                                required>
                            <option value="" disabled {{ old('spesialis', $selectedSpesialis ?? '') ? '' : 'selected' }}>-- Pilih Spesialis --</option>
                            @foreach (($spesialisList ?? collect()) as $sp)
                                <option value="{{ $sp }}" {{ (string) old('spesialis', $selectedSpesialis ?? '') === (string) $sp ? 'selected' : '' }}>{{ $sp }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Pilih spesialis untuk mempermudah filter dokter.</p>
                        @error('spesialis')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dokter -->
                    <div>
                        <label for="dokter_id" class="block text-sm font-semibold text-gray-700 mb-1">Pilih Dokter</label>
                        <select id="dokter_id" name="dokter_id"
                                class="w-full border @error('dokter_id') border-red-500 @else border-gray-300 @enderror focus:ring focus:ring-blue-200 focus:outline-none rounded-lg px-4 py-2 text-sm"
                                {{ (empty($dokters) || (count($dokters ?? []) === 0)) ? 'disabled' : '' }}
                                required>
                            <option value="" disabled {{ old('dokter_id') ? '' : 'selected' }}>-- Pilih Dokter --</option>
                            @foreach (($dokters ?? collect()) as $d)
                                <option value="{{ $d->id }}" {{ (string) old('dokter_id') === (string) $d->id ? 'selected' : '' }}>
                                    {{ $d->name }} ({{ $d->spesialis ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Kuota maksimal 5 pasien per dokter per hari. Dokter penuh otomatis tidak ditampilkan.</p>
                        @error('dokter_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Kunjungan -->
                    <div>
                        <label for="jam_kunjungan" class="block text-sm font-semibold text-gray-700 mb-1">Jam Kunjungan</label>
                        <input type="time" id="jam_kunjungan" name="jam_kunjungan"
                               value="{{ old('jam_kunjungan') }}"
                               class="w-full border @error('jam_kunjungan') border-red-500 @else border-gray-300 @enderror focus:ring focus:ring-blue-200 focus:outline-none rounded-lg px-4 py-2 text-sm"
                               required>
                        @error('jam_kunjungan')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
         <!-- Keluhan -->
                    <div class="md:col-span-2 lg:col-span-3">
                        <label for="keluhan" class="block text-sm font-semibold text-gray-700 mb-1">Keluhan</label>
                        <textarea id="keluhan" name="keluhan" required rows="2"
                                  class="w-full border @error('keluhan') border-red-500 @else border-gray-300 @enderror focus:ring focus:ring-blue-200 focus:outline-none rounded-lg px-4 py-2 text-sm"
                                  placeholder="Contoh: Gigi sakit saat makan/minum...">{{ old('keluhan') }}</textarea>
                        @error('keluhan')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                </div>

                <div class="pt-2 text-center">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-full shadow transition duration-200">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Pendaftaran
                    </button>
                </div>
                
        

                
            </form>
        </div>
    </div>

    <script>
        // Refresh dropdown dokter saat tanggal/spesialis berubah
        (function () {
            const t = document.getElementById('tanggal_kunjungan');
            const s = document.getElementById('spesialis');

            function reloadOptions() {
                const tanggal = t ? t.value : '';
                const spesialis = s ? s.value : '';

                const url = new URL(window.location.href);

                if (tanggal) url.searchParams.set('tanggal_kunjungan', tanggal);
                else url.searchParams.delete('tanggal_kunjungan');

                if (spesialis) url.searchParams.set('spesialis', spesialis);
                else url.searchParams.delete('spesialis');

                if (tanggal || spesialis) {
                    window.location.href = url.toString();
                }
            }

            if (t) t.addEventListener('change', reloadOptions);
            if (s) s.addEventListener('change', reloadOptions);
        })();
    </script>

    {{-- POPUP SUCCESS --}}
    @if (session('success'))
    <div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50"></div>

        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
            <h2 class="text-xl font-bold text-blue-800 mb-2">✅ Pendaftaran Berhasil!</h2>
            <p class="text-gray-700 mb-4">{{ session('success') }}</p>

            @if (session('antrian'))
                <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4 text-blue-800">
                    <div class="font-semibold">Nomor Antrian Anda:</div>
                    <div class="text-2xl font-extrabold tracking-wide mt-1">{{ session('antrian') }}</div>
                    <div class="text-xs text-blue-700 mt-2">Jika Anda melakukan reschedule, nomor antrian akan otomatis berubah.</div>
                </div>
            @endif

            <div class="text-right">
                <button type="button" id="closeSuccessModal"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                    OK
                </button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const modal = document.getElementById('successModal');
            const btn = document.getElementById('closeSuccessModal');

            if (btn && modal) {
                btn.addEventListener('click', function () {
                    modal.remove();
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        modal.remove();
                    }
                });
            }
        })();
    </script>
    @endif
@endsection
