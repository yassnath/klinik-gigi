@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto">
  <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-200" id="kartu-pasien">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-xl font-bold text-blue-700">Kartu Pasien</h2>
        <div class="text-sm text-gray-500">Tunjukkan ke resepsionis/dokter</div>
      </div>
      <img src="{{ asset('images/logo2.png') }}" class="h-10" alt="Logo">
    </div>

    <div class="grid grid-cols-3 gap-4">
      <div class="col-span-2 space-y-2">
        <div class="text-gray-500 text-xs">Nama</div>
        <div class="font-semibold">{{ $user->name }}</div>

        <div class="text-gray-500 text-xs mt-2">No. RM</div>
        <div class="font-semibold tracking-wide">{{ $user->no_rm ?? '-' }}</div>

        <div class="text-gray-500 text-xs mt-2">Username</div>
        <div class="font-semibold">{{ $user->username ?? '-' }}</div>

        <div class="text-gray-500 text-xs mt-2">Alamat</div>
        <div class="font-semibold">
          {{ $user->alamat ?? $user->address ?? $user->alamat_lengkap ?? '-' }}
        </div>
      </div>

      <div class="flex items-center justify-center">
        @if(!empty($qrUrl))
          {{-- ✅ QR PNG (IMG). Bisa dari file public/patient_qr atau data URI fallback --}}
          <img id="qrImage" src="{{ $qrUrl }}" alt="QR Pasien" class="w-32 h-32">
        @endif
      </div>
    </div>

    <div class="mt-6 flex items-center justify-between no-print">
      <a href="#"
         id="downloadQrPng"
         class="text-sm text-blue-600 underline">
        Unduh QR
      </a>
      <button onclick="window.print()"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
        Cetak Kartu
      </button>
    </div>
  </div>
</div>

<style>
@media print {
  body * { visibility: hidden; }
  #kartu-pasien, #kartu-pasien * { visibility: visible; }
  #kartu-pasien { position: absolute; left: 0; top: 0; width: 100%; }
  .no-print { display: none !important; }
}
</style>

<script>
(function () {
  const btn = document.getElementById('downloadQrPng');
  if (!btn) return;

  btn.addEventListener('click', async function (e) {
    e.preventDefault();
    const img = document.getElementById('qrImage');
    if (!img || !img.src) return alert('QR belum tersedia.');

    try {
      const a = document.createElement('a');
      a.href = img.src;
      a.download = `qr-pasien-{{ $user->username ?? $user->id }}.png`;
      document.body.appendChild(a);
      a.click();
      a.remove();
    } catch (err) {
      console.error(err);
      alert('Gagal mengunduh QR.');
    }
  });
})();
</script>
@endsection

