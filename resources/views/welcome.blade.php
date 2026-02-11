<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Klinik HealthEase — Klinik Modern & Nyaman</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet"/>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
    :root {
      --primary: #2BBBAD;
      --secondary: #1976D2;
      --background: #F9FBFC;
      --accent: #A3E635;
      --text: #222222;
      --white: #ffffff;
      --primary-5: rgba(43, 187, 173, 0.12);
      --primary-10: rgba(43, 187, 173, 0.22);
      --accent-10: rgba(163, 230, 53, 0.22);
      --text-70: rgba(34, 34, 34, 0.7);
      --text-55: rgba(34, 34, 34, 0.55);
      --border-soft: rgba(25, 118, 210, 0.18);
      --shadow-soft: 0 18px 45px rgba(25, 118, 210, 0.18);
      --app-gradient:
        radial-gradient(circle at 12% 18%, rgba(163, 230, 53, 0.18), transparent 55%),
        radial-gradient(circle at 88% 12%, rgba(43, 187, 173, 0.2), transparent 50%),
        radial-gradient(circle at 70% 85%, rgba(25, 118, 210, 0.12), transparent 55%);
    }

    html { scroll-behavior: smooth; }
    body { min-height: 100vh; background: var(--background); background-image: var(--app-gradient); color: var(--text); }
    .font-modify { font-family: "Plus Jakarta Sans", sans-serif; }

    .page-fade { animation: page-fade-in 0.8s ease-out both; }
    @keyframes page-fade-in {
      from { opacity: 0; transform: translateY(8px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .nav-glass { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); }
    .bg-app { background: var(--background); background-image: var(--app-gradient); }
    .bg-surface { background: var(--white); }
    .bg-soft { background: var(--primary-5); }
    .bg-primary { background: var(--primary); }
    .bg-secondary { background: var(--secondary); }
    .bg-accent { background: var(--accent); }
    .text-body { color: var(--text); }
    .text-muted { color: var(--text-70); }
    .text-subtle { color: var(--text-55); }
    .text-primary { color: var(--primary); }
    .text-secondary { color: var(--secondary); }
    .text-accent { color: var(--accent); }
    .border-soft { border-color: var(--border-soft); }
    .border-primary { border-color: var(--primary); }

    .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 1rem; font-weight: 600; cursor: pointer; transition: transform .2s ease, box-shadow .2s ease, background-color .2s ease, color .2s ease; }
    .btn-primary { background: var(--primary); color: #fff; box-shadow: 0 14px 30px rgba(43, 187, 173, 0.28); }
    .btn-primary:hover { background: var(--secondary); box-shadow: 0 18px 36px rgba(43, 187, 173, 0.36); transform: translateY(-1px); }
    .btn-outline { background: var(--white); color: var(--primary); border: 1px solid var(--border-soft); box-shadow: 0 10px 22px rgba(25, 118, 210, 0.08); }
    .btn-outline:hover { background: var(--primary-5); border-color: var(--primary); color: var(--secondary); box-shadow: 0 14px 28px rgba(25, 118, 210, 0.12); transform: translateY(-1px); }
    .btn-light { background: var(--white); color: var(--secondary); box-shadow: 0 12px 26px rgba(25, 118, 210, 0.18); }
    .btn-light:hover { background: var(--primary-5); color: var(--secondary); box-shadow: 0 16px 30px rgba(25, 118, 210, 0.2); transform: translateY(-1px); }

    .card { background: var(--white); border: 1px solid var(--border-soft); border-radius: 1.25rem; box-shadow: 0 16px 36px rgba(25, 118, 210, 0.08); }
    .card-soft { background: var(--primary-5); }
    .badge { background: var(--primary-10); color: var(--secondary); }
    .card-hover { transition: transform .2s ease, box-shadow .2s ease; }
    .card-hover:hover { box-shadow: 0 22px 40px rgba(25, 118, 210, 0.2); transform: translateY(-2px); }
    .hover-soft { transition: transform .2s ease, box-shadow .2s ease; }
    .hover-soft:hover { box-shadow: 0 16px 30px rgba(25, 118, 210, 0.18); transform: translateY(-1px); }
    .nav-toggle:hover { background: var(--primary-5); color: var(--primary); }
    .nav-link { border-radius: 999px; transition: color .2s ease, background-color .2s ease; }
    .nav-link:hover { color: var(--secondary); background: var(--primary-5); }
    .nav-link:focus-visible { outline: 2px solid var(--primary); outline-offset: 2px; }

    .hero-glow { background: radial-gradient(circle at top left, rgba(163, 230, 53, 0.26), transparent 55%), radial-gradient(circle at top right, rgba(43, 187, 173, 0.22), transparent 50%); }
    .cta-panel { background: linear-gradient(135deg, var(--secondary), var(--primary)); box-shadow: var(--shadow-soft); border-radius: 2rem; }

    @media (prefers-reduced-motion: reduce) {
      .page-fade, [data-aos] { animation: none !important; transition: none !important; }
    }
  </style>
</head>
<body class="bg-app text-body font-modify page-fade">

  <!-- Navbar -->
  <nav class="nav-glass sticky top-0 z-50 border-b border-soft">
    <div class="max-w-7xl mx-auto px-4 py-4">
      <div class="flex items-center justify-between gap-3">
        <a href="/" class="text-2xl font-bold text-secondary tracking-tight">Klinik HealthEase</a>
        <div class="hidden sm:flex items-center gap-4">
          <a href="#kontak" class="nav-link px-4 py-2 text-primary font-medium text-sm sm:text-base">
            Hubungi Kami
          </a>
          <a href="#biaya-layanan" class="nav-link px-4 py-2 text-primary font-medium text-sm sm:text-base">
            Biaya Layanan
          </a>
          <a href="/login" class="nav-link px-4 py-2 text-primary font-medium text-sm sm:text-base">Login</a>
          <a href="/register" class="btn btn-primary px-4 py-2 text-sm sm:text-base">Konsultasi Sekarang</a>
        </div>

        <button
          id="nav-toggle"
          type="button"
          aria-controls="nav-menu"
          aria-expanded="false"
          class="nav-toggle sm:hidden inline-flex items-center justify-center h-10 w-10 rounded-xl border border-soft text-secondary transition"
        >
          <span class="sr-only">Buka menu</span>
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>

      <div id="nav-menu" class="hidden sm:hidden mt-3 rounded-2xl border border-soft bg-surface shadow-sm p-3">
        <a href="#kontak" class="nav-link block px-3 py-2 text-primary font-medium text-sm">
          Hubungi Kami
        </a>
        <a href="#biaya-layanan" class="nav-link block px-3 py-2 text-primary font-medium text-sm">
          Biaya Layanan
        </a>
        <a href="/login" class="nav-link block px-3 py-2 text-primary font-medium text-sm">Login</a>
        <a href="/register" class="btn btn-primary w-full mt-2 px-4 py-2 text-sm">Konsultasi Sekarang</a>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <section class="relative hero-glow min-h-screen">
    <div class="pointer-events-none absolute -top-10 -right-6 h-44 w-44 rounded-full bg-accent opacity-25 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-16 -left-10 h-56 w-56 rounded-full bg-primary opacity-20 blur-3xl"></div>
    <div class="relative z-10 flex min-h-screen flex-col-reverse md:flex-row items-center justify-between gap-12 px-6 py-16 md:py-24 max-w-7xl mx-auto">
      <div class="md:w-1/2 mb-10 md:mb-0 text-center md:text-left" data-aos="fade-right">
        <h1 class="text-4xl md:text-5xl font-bold text-secondary leading-tight mb-6">
          Sistem Informasi Klinik Modern & Nyaman
        </h1>
        <p class="text-lg text-muted mb-8">
          Daftar, konsultasi, dan pantau hasil perawatan tanpa repot—semua dalam satu sistem yang aman dan terintegrasi.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
          <a href="/register" class="btn btn-primary px-6 py-3 text-lg">
            Konsultasi Sekarang
          </a>
          <a href="#fitur" class="btn btn-outline px-6 py-3 text-lg">
            Lihat Fitur
          </a>
        </div>
      </div>
      <div class="md:w-1/2" data-aos="zoom-in">
        <img src="{{ asset('images/login.png') }}" alt="Ilustrasi Klinik" class="w-full max-w-md mx-auto drop-shadow-2xl">
      </div>
    </div>
  </section>

  <!-- Nilai Utama -->
  <section class="py-16 bg-surface" id="fitur">
    <div class="max-w-7xl mx-auto px-6">
      <h2 class="text-3xl font-bold mb-8 text-secondary text-center" data-aos="fade-up">Mengapa HealtEase?</h2>
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card card-soft p-6" data-aos="fade-up">
          <h3 class="font-semibold text-primary mb-2">Cepat & Tanpa Antre</h3>
          <p class="text-sm text-muted">Pendaftaran online dengan tiket digital dan notifikasi otomatis.</p>
        </div>
        <div class="card card-soft p-6" data-aos="fade-up" data-aos-delay="50">
          <h3 class="font-semibold text-primary mb-2">Dokter Terverifikasi</h3>
          <p class="text-sm text-muted">Tenaga medis berpengalaman dan komunikatif.</p>
        </div>
        <div class="card card-soft p-6" data-aos="fade-up" data-aos-delay="100">
          <h3 class="font-semibold text-primary mb-2">Rekam Medis Digital</h3>
          <p class="text-sm text-muted">Hasil pemeriksaan, resep, dan riwayat kunjungan tersimpan aman.</p>
        </div>
        <div class="card card-soft p-6" data-aos="fade-up" data-aos-delay="150">
          <h3 class="font-semibold text-primary mb-2">Pembayaran Terintegrasi</h3>
          <p class="text-sm text-muted">Fleksibel: tunai, transfer, dan nontunai populer.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Layanan -->
  <section class="py-16 bg-app">
    <div class="max-w-7xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-secondary mb-10 text-center" data-aos="fade-up">Layanan Utama</h2>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="card p-6 card-hover" data-aos="zoom-in">
          <h3 class="font-semibold text-lg mb-2 text-primary">Konsultasi Dokter Umum</h3>
          <p class="text-sm text-muted">Keluhan harian, skrining awal, dan rujukan terarah.</p>
        </div>
        <div class="card p-6 card-hover" data-aos="zoom-in" data-aos-delay="50">
          <h3 class="font-semibold text-lg mb-2 text-primary">Poli Gigi</h3>
          <p class="text-sm text-muted">Scaling, tambal, cabut, serta edukasi gigi anak.</p>
        </div>
        <div class="card p-6 card-hover" data-aos="zoom-in" data-aos-delay="100">
          <h3 class="font-semibold text-lg mb-2 text-primary">Laboratorium Dasar</h3>
          <p class="text-sm text-muted">Tes rutin hemat waktu, hasil tersimpan digital.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- NEW: Biaya Layanan -->
  <section id="biaya-layanan" class="py-16 bg-surface">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-10" data-aos="fade-up">
        <h2 class="text-3xl font-bold text-secondary mb-3">Biaya Layanan Klinik Gigi (Estimasi)</h2>
        <p class="text-muted max-w-3xl mx-auto">
          Kisaran biaya berikut adalah estimasi umum dan dapat berbeda tergantung tingkat kesulitan kasus, bahan yang digunakan, fasilitas,
          serta kebijakan klinik. Informasi ini bukan diagnosis medis dan tidak menggantikan pemeriksaan dokter.
        </p>
      </div>

      <div class="bg-soft border border-soft rounded-2xl p-6 md:p-8" data-aos="zoom-in">
        <div class="hidden md:block overflow-x-auto">
          <table class="w-full min-w-[720px] text-left text-xs sm:text-sm">
            <thead>
              <tr class="text-secondary">
                <th class="py-3 px-4 font-semibold">Kategori</th>
                <th class="py-3 px-4 font-semibold">Layanan/Tindakan</th>
                <th class="py-3 px-4 font-semibold">Kisaran Biaya</th>
                <th class="py-3 px-4 font-semibold">Catatan</th>
              </tr>
            </thead>
            <tbody class="text-body">
              <tr class="border-t border-soft">
                <td class="py-3 px-4">Umum</td>
                <td class="py-3 px-4">Konsultasi / pemeriksaan</td>
                <td class="py-3 px-4 font-semibold">Rp 100.000 – 200.000</td>
                <td class="py-3 px-4">Estimasi awal (bisa berbeda tiap klinik)</td>
              </tr>

              <tr class="border-t border-soft">
                <td class="py-3 px-4">Preventif</td>
                <td class="py-3 px-4">Scaling / pembersihan karang gigi</td>
                <td class="py-3 px-4 font-semibold">Rp 200.000 – 600.000</td>
                <td class="py-3 px-4">Tergantung tingkat karang & fasilitas</td>
              </tr>

              <tr class="border-t border-soft">
                <td class="py-3 px-4">Restorasi</td>
                <td class="py-3 px-4">Tambal gigi (komposit/umum)</td>
                <td class="py-3 px-4 font-semibold">Rp 150.000 – 800.000</td>
                <td class="py-3 px-4">Dipengaruhi bahan & kedalaman lubang</td>
              </tr>

              <tr class="border-t border-soft">
                <td class="py-3 px-4">Tindakan</td>
                <td class="py-3 px-4">Cabut gigi biasa (non-bedah)</td>
                <td class="py-3 px-4 font-semibold">Rp 350.000 – 650.000</td>
                <td class="py-3 px-4">Tergantung posisi gigi & kondisi akar</td>
              </tr>

              <tr class="border-t border-soft">
                <td class="py-3 px-4">Endodonti</td>
                <td class="py-3 px-4">Perawatan saluran akar (PSA / root canal)</td>
                <td class="py-3 px-4 font-semibold">Rp 800.000 – 3.500.000+</td>
                <td class="py-3 px-4">Tergantung gigi (depan/belakang) & kompleksitas</td>
              </tr>

              <tr class="border-t border-soft">
                <td class="py-3 px-4">Bedah mulut</td>
                <td class="py-3 px-4">Odontektomi (operasi gigi bungsu)</td>
                <td class="py-3 px-4 font-semibold">Rp 1.000.000 – 6.000.000</td>
                <td class="py-3 px-4">Dipengaruhi tingkat impaksi & tindakan penunjang</td>
              </tr>

              <tr class="border-t border-soft">
                <td class="py-3 px-4">Ortodonti</td>
                <td class="py-3 px-4">Behel / kawat gigi</td>
                <td class="py-3 px-4 font-semibold">Rp 3.000.000 – 15.000.000+</td>
                <td class="py-3 px-4">Tergantung jenis braket & rencana perawatan</td>
              </tr>

              <tr class="border-t border-soft">
                <td class="py-3 px-4">Prostodonsia</td>
                <td class="py-3 px-4">Crown / mahkota gigi</td>
                <td class="py-3 px-4 font-semibold">Rp 1.000.000 – 5.000.000+</td>
                <td class="py-3 px-4">Harga sangat dipengaruhi bahan (PFM/zirconia/dll)</td>
              </tr>

              <tr class="border-t border-soft">
                <td class="py-3 px-4">Prostodonsia</td>
                <td class="py-3 px-4">Gigi palsu (lepasan/parsial/komplit)</td>
                <td class="py-3 px-4 font-semibold">Rp 500.000 – 8.000.000+</td>
                <td class="py-3 px-4">Tergantung jenis & jumlah gigi</td>
              </tr>

              <tr class="border-t border-soft">
                <td class="py-3 px-4">Implantologi</td>
                <td class="py-3 px-4">Implan gigi</td>
                <td class="py-3 px-4 font-semibold">Rp 12.000.000 – 30.000.000+</td>
                <td class="py-3 px-4">Belum termasuk tindakan tambahan (jika diperlukan)</td>
              </tr>

              <tr class="border-t border-soft">
                <td class="py-3 px-4">Estetik</td>
                <td class="py-3 px-4">Veneer</td>
                <td class="py-3 px-4 font-semibold">Rp 650.000 – 5.000.000+</td>
                <td class="py-3 px-4">Tergantung jenis veneer & bahan</td>
              </tr>

              <tr class="border-t border-soft">
                <td class="py-3 px-4">Estetik</td>
                <td class="py-3 px-4">Bleaching / pemutihan gigi</td>
                <td class="py-3 px-4 font-semibold">Rp 300.000 – 5.000.000</td>
                <td class="py-3 px-4">Tergantung metode (home/in-office) & fasilitas</td>
              </tr>

              <tr class="border-t border-soft">
                <td class="py-3 px-4">Periodonsia</td>
                <td class="py-3 px-4">Perawatan gusi lanjutan (tindakan tertentu)</td>
                <td class="py-3 px-4 font-semibold">Rp 400.000 – 3.000.000+</td>
                <td class="py-3 px-4">Bergantung diagnosis dokter & tingkat keparahan</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="md:hidden space-y-4">
          <div class="card p-4">
            <div class="text-xs text-subtle">Umum</div>
            <div class="text-base font-semibold text-secondary">Konsultasi / pemeriksaan</div>
            <div class="text-sm text-muted mt-1">Estimasi awal (bisa berbeda tiap klinik)</div>
            <div class="text-sm font-semibold text-primary mt-3">Rp 100.000 – 200.000</div>
          </div>

          <div class="card p-4">
            <div class="text-xs text-subtle">Preventif</div>
            <div class="text-base font-semibold text-secondary">Scaling / pembersihan karang gigi</div>
            <div class="text-sm text-muted mt-1">Tergantung tingkat karang & fasilitas</div>
            <div class="text-sm font-semibold text-primary mt-3">Rp 200.000 – 600.000</div>
          </div>

          <div class="card p-4">
            <div class="text-xs text-subtle">Restorasi</div>
            <div class="text-base font-semibold text-secondary">Tambal gigi (komposit/umum)</div>
            <div class="text-sm text-muted mt-1">Dipengaruhi bahan & kedalaman lubang</div>
            <div class="text-sm font-semibold text-primary mt-3">Rp 150.000 – 800.000</div>
          </div>

          <div class="card p-4">
            <div class="text-xs text-subtle">Tindakan</div>
            <div class="text-base font-semibold text-secondary">Cabut gigi biasa (non-bedah)</div>
            <div class="text-sm text-muted mt-1">Tergantung posisi gigi & kondisi akar</div>
            <div class="text-sm font-semibold text-primary mt-3">Rp 350.000 – 650.000</div>
          </div>

          <div class="card p-4">
            <div class="text-xs text-subtle">Endodonti</div>
            <div class="text-base font-semibold text-secondary">Perawatan saluran akar (PSA / root canal)</div>
            <div class="text-sm text-muted mt-1">Tergantung gigi (depan/belakang) & kompleksitas</div>
            <div class="text-sm font-semibold text-primary mt-3">Rp 800.000 – 3.500.000+</div>
          </div>

          <div class="card p-4">
            <div class="text-xs text-subtle">Bedah mulut</div>
            <div class="text-base font-semibold text-secondary">Odontektomi (operasi gigi bungsu)</div>
            <div class="text-sm text-muted mt-1">Dipengaruhi tingkat impaksi & tindakan penunjang</div>
            <div class="text-sm font-semibold text-primary mt-3">Rp 1.000.000 – 6.000.000</div>
          </div>

          <div class="card p-4">
            <div class="text-xs text-subtle">Ortodonti</div>
            <div class="text-base font-semibold text-secondary">Behel / kawat gigi</div>
            <div class="text-sm text-muted mt-1">Tergantung jenis braket & rencana perawatan</div>
            <div class="text-sm font-semibold text-primary mt-3">Rp 3.000.000 – 15.000.000+</div>
          </div>

          <div class="card p-4">
            <div class="text-xs text-subtle">Prostodonsia</div>
            <div class="text-base font-semibold text-secondary">Crown / mahkota gigi</div>
            <div class="text-sm text-muted mt-1">Harga sangat dipengaruhi bahan (PFM/zirconia/dll)</div>
            <div class="text-sm font-semibold text-primary mt-3">Rp 1.000.000 – 5.000.000+</div>
          </div>

          <div class="card p-4">
            <div class="text-xs text-subtle">Prostodonsia</div>
            <div class="text-base font-semibold text-secondary">Gigi palsu (lepasan/parsial/komplit)</div>
            <div class="text-sm text-muted mt-1">Tergantung jenis & jumlah gigi</div>
            <div class="text-sm font-semibold text-primary mt-3">Rp 500.000 – 8.000.000+</div>
          </div>

          <div class="card p-4">
            <div class="text-xs text-subtle">Implantologi</div>
            <div class="text-base font-semibold text-secondary">Implan gigi</div>
            <div class="text-sm text-muted mt-1">Belum termasuk tindakan tambahan (jika diperlukan)</div>
            <div class="text-sm font-semibold text-primary mt-3">Rp 12.000.000 – 30.000.000+</div>
          </div>

          <div class="card p-4">
            <div class="text-xs text-subtle">Estetik</div>
            <div class="text-base font-semibold text-secondary">Veneer</div>
            <div class="text-sm text-muted mt-1">Tergantung jenis veneer & bahan</div>
            <div class="text-sm font-semibold text-primary mt-3">Rp 650.000 – 5.000.000+</div>
          </div>

          <div class="card p-4">
            <div class="text-xs text-subtle">Estetik</div>
            <div class="text-base font-semibold text-secondary">Bleaching / pemutihan gigi</div>
            <div class="text-sm text-muted mt-1">Tergantung metode (home/in-office) & fasilitas</div>
            <div class="text-sm font-semibold text-primary mt-3">Rp 300.000 – 5.000.000</div>
          </div>

          <div class="card p-4">
            <div class="text-xs text-subtle">Periodonsia</div>
            <div class="text-base font-semibold text-secondary">Perawatan gusi lanjutan (tindakan tertentu)</div>
            <div class="text-sm text-muted mt-1">Bergantung diagnosis dokter & tingkat keparahan</div>
            <div class="text-sm font-semibold text-primary mt-3">Rp 400.000 – 3.000.000+</div>
          </div>
        </div>

        <div class="mt-4 text-xs text-muted">
          <span class="font-semibold">Catatan:</span> Untuk harga pasti, silakan konsultasi langsung di klinik.
        </div>
      </div>
    </div>
  </section>

  <!-- Pricing / Licensing -->
  <section id="pricing" class="py-16 bg-surface">
    <div class="max-w-7xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-secondary mb-4 text-center" data-aos="fade-up">Paket Lisensi HealthEase</h2>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Trial -->
        <div class="card card-soft p-6 sm:p-8" data-aos="zoom-in">
          <h3 class="text-xl font-semibold text-secondary mb-2">Free Trial</h3>
          <p class="text-muted mb-6">Coba sistem sebelum kontrak dimulai untuk memastikan sesuai kebutuhan klinik.</p>

          <div class="mb-6">
            <p class="text-4xl font-bold text-secondary">Gratis</p>
            <p class="text-sm text-subtle mt-1">Demo uji coba (tanpa komitmen)</p>
          </div>

          <ul class="space-y-3 text-sm text-body">
            <li class="flex gap-2"><span class="text-primary font-bold">✓</span> Akses fitur inti (antrian, pasien, dashboard)</li>
            <li class="flex gap-2"><span class="text-primary font-bold">✓</span> Simulasi alur kerja admin, resepsionis, dokter</li>
            <li class="flex gap-2"><span class="text-primary font-bold">✓</span> Pendampingan setup awal</li>
          </ul>

          <div class="mt-8">
            <a href="https://wa.me/6282221657340" class="btn btn-outline px-5 py-3">
              Minta Demo
            </a>
          </div>
        </div>

        <!-- 1 Tahun -->
        <div class="card p-6 sm:p-8" data-aos="zoom-in" data-aos-delay="50">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-xl font-semibold text-secondary">Lisensi 1 Tahun</h3>
            <span class="text-xs badge px-3 py-1 rounded-full font-semibold">Populer</span>
          </div>

          <p class="text-muted mb-6">Cocok untuk klinik yang ingin mulai digitalisasi operasional dengan biaya terukur.</p>

          <div class="mb-6">
            <p class="text-4xl font-bold text-secondary">Custom</p>
            <p class="text-sm text-subtle mt-1">Harga menyesuaikan kebutuhan & skala klinik</p>
          </div>

          <ul class="space-y-3 text-sm text-body">
            <li class="flex gap-2"><span class="text-primary font-bold">✓</span> Sistem antrian & pendaftaran online</li>
            <li class="flex gap-2"><span class="text-primary font-bold">✓</span> Manajemen pasien & rekam medis digital</li>
            <li class="flex gap-2"><span class="text-primary font-bold">✓</span> Dashboard admin, dokter, resepsionis</li>
            <li class="flex gap-2"><span class="text-primary font-bold">✓</span> Update fitur berkala & pemeliharaan sistem</li>
            <li class="flex gap-2"><span class="text-primary font-bold">✓</span> Dukungan teknis selama masa kontrak</li>
          </ul>

          <div class="mt-8">
            <a href="https://wa.me/6282221657340" class="btn btn-primary px-5 py-3">
              Konsultasi Paket
            </a>
          </div>
        </div>

        <!-- 2 Tahun -->
        <div class="card card-soft p-6 sm:p-8" data-aos="zoom-in" data-aos-delay="100">
          <h3 class="text-xl font-semibold text-secondary mb-2">Lisensi 2 Tahun</h3>
          <p class="text-muted mb-6">Pilihan hemat untuk kerja sama jangka panjang dengan dukungan lebih stabil.</p>

          <div class="mb-6">
            <p class="text-4xl font-bold text-secondary">Custom</p>
            <p class="text-sm text-subtle mt-1">Kontrak lebih panjang, value lebih tinggi</p>
          </div>

          <ul class="space-y-3 text-sm text-body">
            <li class="flex gap-2"><span class="text-primary font-bold">✓</span> Semua fitur paket 1 tahun</li>
            <li class="flex gap-2"><span class="text-primary font-bold">✓</span> Prioritas dukungan & maintenance</li>
            <li class="flex gap-2"><span class="text-primary font-bold">✓</span> Penyesuaian minor sesuai kebutuhan klinik</li>
            <li class="flex gap-2"><span class="text-primary font-bold">✓</span> Pembaruan fitur berkelanjutan</li>
          </ul>

          <div class="mt-8">
            <a href="https://wa.me/6282221657340" class="btn btn-outline px-5 py-3">
              Ajukan Penawaran
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="py-16">
    <div class="max-w-7xl mx-auto px-6">
      <div class="cta-panel relative overflow-hidden px-6 py-12 sm:px-10 sm:py-14 text-center text-white">
        <div class="pointer-events-none absolute -top-12 -right-12 h-40 w-40 rounded-full bg-accent opacity-30 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-12 -left-12 h-40 w-40 rounded-full bg-white opacity-10 blur-3xl"></div>
        <h3 class="text-3xl font-bold mb-4" data-aos="fade-up">Siap mulai perawatan yang lebih nyaman?</h3>
        <p class="mb-8 text-white opacity-90" data-aos="fade-up" data-aos-delay="50">Buat janji sekarang dan rasakan proses klinik yang rapi dan terukur.</p>
        <a href="/register" class="btn btn-light px-6 py-3">Konsultasi Sekarang</a>
      </div>
    </div>
  </section>

  <!-- Hubungi Kami (ICON IG + WA) -->
  <section id="kontak" class="py-16 bg-surface">
    <div class="max-w-3xl mx-auto px-6 text-center" data-aos="fade-up">
      <h2 class="text-3xl font-bold mb-3 text-secondary">Hubungi Kami</h2>
      <p class="text-muted mb-8">
        Tertarik menggunakan sistem ini untuk klinik Anda? Hubungi kami melalui Instagram atau WhatsApp di bawah ini ya!
      </p>

      <div class="grid sm:grid-cols-2 gap-4 max-w-xl mx-auto">
        <!-- Instagram -->
        <a
          href="https://instagram.com/solvix.studio"
          target="_blank"
          rel="noopener"
          class="group flex items-center justify-center gap-3 px-5 py-4 rounded-2xl border border-soft bg-surface shadow-sm hover-soft"
        >
          <!-- IG ICON -->
          <svg class="w-6 h-6 text-accent" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2Zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5A4.25 4.25 0 0 0 7.75 20.5h8.5A4.25 4.25 0 0 0 20.5 16.25v-8.5A4.25 4.25 0 0 0 16.25 3.5h-8.5Zm9.65 2.55a.85.85 0 1 1 0 1.7.85.85 0 0 1 0-1.7ZM12 6.5A5.5 5.5 0 1 1 6.5 12 5.51 5.51 0 0 1 12 6.5Zm0 1.5A4 4 0 1 0 16 12a4 4 0 0 0-4-4Z"/>
          </svg>

          <div class="text-left">
            <div class="text-xs text-subtle">Instagram</div>
            <div class="font-semibold text-body group-hover:text-primary transition">
              @solvix.studio
            </div>
          </div>
        </a>

        <!-- WhatsApp -->
        <a
          href="https://wa.me/6282221657340"
          target="_blank"
          rel="noopener"
          class="group flex items-center justify-center gap-3 px-5 py-4 rounded-2xl border border-soft bg-surface shadow-sm hover-soft"
        >
          <!-- WA ICON -->
          <svg class="w-6 h-6 text-primary" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M12.04 2C6.53 2 2.06 6.31 2.06 11.63c0 2.09.74 4.04 2 5.62L3 22l4.98-1.56a10.3 10.3 0 0 0 4.06.82c5.5 0 9.98-4.31 9.98-9.63C22.02 6.31 17.54 2 12.04 2Zm0 17.56c-1.28 0-2.51-.27-3.63-.79l-.26-.12-2.95.93.98-2.76-.18-.28a7.79 7.79 0 0 1-1.26-4.27c0-4.33 3.66-7.86 8.3-7.86 4.58 0 8.3 3.53 8.3 7.86 0 4.33-3.72 7.86-8.3 7.86Zm4.63-5.64c-.25-.12-1.48-.72-1.71-.8-.23-.08-.4-.12-.57.12-.17.24-.66.8-.8.97-.15.17-.3.19-.55.06-.25-.12-1.05-.38-2-1.22-.74-.64-1.24-1.44-1.39-1.68-.14-.24-.02-.37.1-.49.11-.11.25-.28.37-.42.12-.14.17-.24.25-.4.08-.16.04-.3-.02-.42-.06-.12-.57-1.34-.78-1.83-.2-.48-.41-.41-.57-.42h-.49c-.17 0-.45.06-.68.3-.23.24-.9.88-.9 2.14 0 1.26.93 2.48 1.06 2.65.12.17 1.83 2.86 4.45 4.01.62.27 1.1.43 1.48.55.62.2 1.19.17 1.64.1.5-.07 1.48-.6 1.69-1.19.2-.59.2-1.1.14-1.19-.06-.09-.23-.14-.48-.27Z"/>
          </svg>

          <div class="text-left">
            <div class="text-xs text-subtle">WhatsApp</div>
            <div class="font-semibold text-body group-hover:text-primary transition">
              0822-2165-7340
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>

  <footer class="text-center py-6 text-sm text-subtle">
    &copy; 2025 Solvix Studio. All rights reserved.
  </footer>

  <!-- ================= AI CS WIDGET (TAMBAHAN SAJA) ================= -->
  <div id="ai-cs" style="position:fixed;right:18px;bottom:18px;z-index:9999;">
    <button id="ai-cs-btn"
      style="background:var(--primary);color:white;border:none;border-radius:999px;
             padding:12px 16px;cursor:pointer;font-weight:600;">
      AI CS
    </button>

    <div id="ai-cs-panel"
      style="display:none;width:320px;max-width:90vw;background:#fff;
             border:1px solid var(--border-soft);border-radius:12px;
             box-shadow:0 16px 30px rgba(25,118,210,.12);
             overflow:hidden;margin-top:10px;">

      <div style="padding:10px 12px;border-bottom:1px solid var(--border-soft);font-weight:700;">
        Customer Support
        <button id="ai-cs-close"
          style="float:right;border:none;background:transparent;cursor:pointer;">✕</button>
      </div>

      <div id="ai-cs-log"
        style="padding:10px 12px;height:260px;overflow:auto;font-size:14px;"></div>

      <div style="display:flex;gap:8px;padding:10px 12px;border-top:1px solid var(--border-soft);">
        <input id="ai-cs-input" type="text" placeholder="Tanya jadwal dokter, biaya, atau konsultasi..."
          style="flex:1;border:1px solid var(--border-soft);border-radius:10px;
                 padding:8px 10px;font-size:14px;">
        <button id="ai-cs-send"
          style="background:var(--primary);color:white;border:none;
                 border-radius:10px;padding:8px 12px;font-weight:600;">
          Kirim
        </button>
      </div>
    </div>
  </div>

  <script>
  (function(){
    const btn = document.getElementById('ai-cs-btn');
    const panel = document.getElementById('ai-cs-panel');
    const close = document.getElementById('ai-cs-close');
    const log = document.getElementById('ai-cs-log');
    const input = document.getElementById('ai-cs-input');
    const send = document.getElementById('ai-cs-send');

    function esc(s){ return (s||'').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

    function addMsg(who, text){
      const el = document.createElement('div');
      el.style.marginBottom = '10px';
      el.innerHTML = '<b>'+esc(who)+'</b><div style="white-space:pre-wrap;">'+esc(text)+'</div>';
      log.appendChild(el);
      log.scrollTop = log.scrollHeight;
    }

    btn.onclick = () => {
      panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
      if (log.childElementCount === 0) {
        addMsg('AI CS', 'Halo Kak! Aku bisa bantu info jadwal dokter, dokter yang ready, biaya layanan, dan cara konsultasi.\nUntuk daftar, ketik konsultasi ya.');
      }
    };

    close.onclick = () => panel.style.display = 'none';

    async function sendMsg(){
      const msg = input.value.trim();
      if (!msg) return;
      input.value = '';
      addMsg('Kamu', msg);
      addMsg('AI CS', '...');

      try{
        // ✅ PAKAI PATH ABSOLUTE (BIAR TIDAK NYASAR KE /resepsionis/ai/cs)
        const res = await fetch('/ai/cs', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ message: msg })
        });

        const text = await res.text();
        log.removeChild(log.lastChild);

        let data = {};
        try { data = JSON.parse(text); } catch(e) {}

        if(!res.ok){
          addMsg('AI CS', 'Error ' + res.status + ': ' + (data.error || text || 'Server error'));
          return;
        }

        addMsg('AI CS', data.reply || 'Maaf, terjadi kendala.');
      }catch(e){
        log.removeChild(log.lastChild);
        addMsg('AI CS', 'Koneksi gagal: ' + (e.message || e));
      }
    }

    send.onclick = sendMsg;
    input.addEventListener('keydown', e => e.key === 'Enter' && sendMsg());
  })();
  </script>
  <!-- ================= END AI CS WIDGET ================= -->

  <script>
  (function(){
    const toggle = document.getElementById('nav-toggle');
    const menu = document.getElementById('nav-menu');
    if (!toggle || !menu) return;

    toggle.addEventListener('click', () => {
      const isOpen = !menu.classList.contains('hidden');
      menu.classList.toggle('hidden');
      toggle.setAttribute('aria-expanded', String(!isOpen));
    });

    menu.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => {
        if (window.innerWidth < 640) {
          menu.classList.add('hidden');
          toggle.setAttribute('aria-expanded', 'false');
        }
      });
    });
  })();
  </script>

  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>AOS.init({ duration: 900, once: true });</script>
</body>
</html>
