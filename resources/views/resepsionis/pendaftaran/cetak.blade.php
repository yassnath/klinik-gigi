<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Kartu Pasien</title>

    {{-- Tailwind CDN supaya styling sama seperti kartu pasien --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
      @media print {
        /* sembunyikan semua elemen halaman */
        body * {
          visibility: hidden !important;
        }

        /* tampilkan hanya kartu pasien */
        #kartu-pasien, #kartu-pasien * {
          visibility: visible !important;
        }

        /* posisikan kartu di kiri atas & rapikan */
        #kartu-pasien {
          position: absolute;
          left: 0;
          top: 0;
          width: 100%;
          box-shadow: none !important;
          border: 1px solid #e5e7eb !important;
          margin: 0 !important;
        }

        /* sembunyikan tombol/link saat print */
        .no-print {
          display: none !important;
        }
      }
    </style>
</head>
<body class="bg-blue-50 min-h-screen flex items-center justify-center p-6">
@php
  $user = $pendaftaran->user;

  // =========================
  // FIX QR di hosting (cPanel)
  // =========================
  // Kasus umum: url /storage/... (qr_url) 404 karena symlink public/storage tidak ada.
  // Fallback ke file yang disimpan di public/patient_qr/... jika qr_path mengarah ke sana.
  $qrSrc = null;
  $qrSrcFallbackPublic = null;
  $qrSrcFallbackStorage = null;

  if (optional($user)->qr_url) {
      $qrSrc = $user->qr_url;
  }

  if (!empty(optional($user)->qr_path)) {
      // data bisa berupa "patient_qr/xxx.png" (public) atau "patient_qr/xxx.png" yang dulu dianggap storage
      $qrSrcFallbackPublic  = asset($user->qr_path);
      $qrSrcFallbackStorage = asset('storage/' . $user->qr_path);
  }

  // Untuk link download, pakai yang paling mungkin valid di hosting
  $qrDownload = $qrSrcFallbackPublic ?? $qrSrcFallbackStorage ?? $qrSrc;
@endphp

<div class="max-w-lg mx-auto w-full">
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
        <div class="font-semibold">{{ $pendaftaran->nama ?? optional($user)->name ?? '-' }}</div>

        <div class="text-gray-500 text-xs mt-2">No. RM</div>
        <div class="font-semibold tracking-wide">{{ optional($user)->no_rm ?? '-' }}</div>

        <div class="text-gray-500 text-xs mt-2">No. HP</div>
        <div class="font-semibold">{{ optional($user)->telepon ?? optional($user)->no_hp ?? $pendaftaran->no_hp ?? '-' }}</div>

        <div class="text-gray-500 text-xs mt-2">Alamat</div>
        <div class="font-semibold">{{ optional($user)->alamat ?? '-' }}</div>
      </div>

      <div class="flex items-center justify-center">
        @if($qrSrc || $qrSrcFallbackPublic || $qrSrcFallbackStorage)
          <img
            src="{{ $qrSrcFallbackPublic ?? $qrSrcFallbackStorage ?? $qrSrc }}"
            alt="QR Pasien"
            class="w-32 h-32"
            onerror="
              this.onerror=null;
              if ('{{ $qrSrcFallbackPublic }}') { this.src='{{ $qrSrcFallbackPublic }}'; }
              else if ('{{ $qrSrcFallbackStorage }}') { this.src='{{ $qrSrcFallbackStorage }}'; }
            "
          >
        @endif
      </div>
    </div>

    <div class="mt-6 flex items-center justify-between no-print">
      @if($qrDownload)
        <a href="{{ $qrDownload }}" download class="text-sm text-blue-600 underline">Unduh QR</a>
      @else
        <span class="text-sm text-gray-400">QR belum tersedia</span>
      @endif

      <button onclick="window.print()" class="text-sm px-3 py-1 rounded bg-blue-600 text-white">Cetak</button>
    </div>
  </div>
</div>

</body>
</html>

