<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SIM HealtEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

    .font-modify {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-weight: 400;
        font-style: normal;
    }

    :root {
        --primary: #2BBBAD;
        --secondary: #1976D2;
        --background: #F9FBFC;
        --surface: #FFFFFF;
        --text: #222222;
        --accent: #A3E635;
        --primary-5: rgba(43, 187, 173, 0.12);
        --primary-10: rgba(43, 187, 173, 0.22);
        --border-soft: rgba(25, 118, 210, 0.2);
        --shadow-soft: 0 12px 28px rgba(25, 118, 210, 0.12);
    }

    body {
        background: var(--background);
        color: var(--text);
    }

    .bg-app { background: var(--background); }
    .bg-surface { background: var(--surface); }
    .text-body { color: var(--text); }
    .text-primary { color: var(--primary); }
    .text-secondary { color: var(--secondary); }
    .border-soft { border-color: var(--border-soft); }

    .card-soft {
        background: var(--surface);
        border: 1px solid var(--border-soft);
        border-radius: 14px;
        box-shadow: var(--shadow-soft);
    }

    table.theme-table {
        width: 100%;
        background: var(--surface);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--border-soft);
        border-collapse: collapse;
        border-spacing: 0;
    }

    table.theme-table thead {
        background: var(--primary);
        color: #fff;
    }

    table.theme-table th,
    table.theme-table td {
        border-color: var(--border-soft);
    }

    table.theme-table tbody tr:hover {
        background: var(--primary-5);
    }

    /* Theme overrides for existing Tailwind utility classes */
    .bg-blue-50 { background-color: rgba(43, 187, 173, 0.08) !important; }
    .bg-blue-100 { background-color: var(--primary-10) !important; }
    .bg-blue-200 { background-color: rgba(43, 187, 173, 0.18) !important; }
    .bg-blue-500 { background-color: var(--primary) !important; }
    .bg-blue-600 { background-color: var(--primary) !important; }
    .bg-blue-700 { background-color: var(--secondary) !important; }

    .text-blue-500 { color: var(--primary) !important; }
    .text-blue-600 { color: var(--primary) !important; }
    .text-blue-700 { color: var(--secondary) !important; }
    .text-blue-800 { color: var(--secondary) !important; }

    .border-blue-200 { border-color: var(--border-soft) !important; }
    .border-blue-300 { border-color: rgba(43, 187, 173, 0.35) !important; }

    .hover\:bg-blue-100:hover { background-color: var(--primary-10) !important; }
    .hover\:bg-blue-600:hover { background-color: var(--secondary) !important; }
    .hover\:bg-blue-700:hover { background-color: var(--secondary) !important; }
    .hover\:text-blue-600:hover { color: var(--secondary) !important; }
    .hover\:text-blue-700:hover { color: var(--secondary) !important; }

    .hover\:bg-gray-50:hover { background-color: var(--primary-5) !important; }
    .hover\:bg-gray-100:hover { background-color: var(--primary-5) !important; }

    .divide-gray-200 > :not([hidden]) ~ :not([hidden]) {
        border-color: var(--border-soft) !important;
    }

    .focus\:ring-blue-200:focus { --tw-ring-color: var(--primary-10) !important; }
    .focus\:ring-blue-500:focus { --tw-ring-color: var(--primary) !important; }

    @media (max-width: 640px) {
        table.responsive-table thead {
            display: none;
        }

        table.responsive-table,
        table.responsive-table tbody,
        table.responsive-table tr,
        table.responsive-table td,
        table.responsive-table th {
            display: block;
            width: 100%;
        }

        table.responsive-table tr {
            background: var(--surface);
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 12px;
            box-shadow: 0 12px 24px rgba(25, 118, 210, 0.12);
        }

        table.responsive-table tr:last-child {
            margin-bottom: 0;
        }

        table.responsive-table td {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            padding: 6px 0;
            border: none !important;
            text-align: right;
        }

        table.responsive-table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: rgba(34, 34, 34, 0.6);
            text-align: left;
        }
    }
</style>

<body class="bg-app text-body font-modify">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 hidden z-40 sm:hidden"></div>

    <div class="min-h-screen sm:flex">
        {{-- Sidebar --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-surface shadow-lg p-6 border-r border-soft transform -translate-x-full transition-transform duration-300 ease-out z-50 sm:static sm:translate-x-0">
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Klinik" class="h-12 mb-2">
            </div>

            <nav class="space-y-4">

                {{-- ✅ HANYA JALAN KALAU SUDAH LOGIN --}}
                @auth
                    {{-- Role: Pasien --}}
                    @if(Auth::user()->role === 'pasien')

                        @php
                            $unreadNotifCount = \App\Models\Notifikasi::where('user_id', Auth::id())
                                ->where('dibaca', false)
                                ->count();
                        @endphp

                        <a href="/pasien" class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-home mr-2"></i> Dashboard
                        </a>
                        <a href="/jadwal-dokter"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-calendar-alt mr-2"></i> Jadwal Dokter
                        </a>

                        <a href="{{ route('pendaftaran.saya') }}"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-list mr-2"></i> Pendaftaran Saya
                        </a>

                        <a href="/rekam-medis"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-folder mr-2"></i> Rekam Medis
                        </a>
                        <a href="/pasien/tagihan"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-credit-card mr-2"></i> Pembayaran
                        </a>

                        <a href="/notifikasi"
                            class="flex items-center justify-between py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <span class="flex items-center">
                                <i class="fas fa-bell mr-2"></i> Notifikasi
                            </span>

                            @if($unreadNotifCount > 0)
                                <span class="inline-flex items-center justify-center text-xs font-bold text-white bg-red-500 rounded-full min-w-[20px] h-5 px-1">
                                    {{ $unreadNotifCount }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('pasien.kartu') }}"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-id-card mr-2"></i> Kartu Pasien
                        </a>

                    {{-- Role: Dokter --}}
                    @elseif(Auth::user()->role === 'dokter')
                        <a href="/dokter" class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-home mr-2"></i> Dashboard
                        </a>
                        <a href="{{ route('dokter.jadwal.index') }}"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-calendar-check mr-2"></i> Manajemen Jadwal
                        </a>
                        <a href="/dokter/pendaftar"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-list-alt mr-2"></i> Semua Pendaftaran
                        </a>
                        <a href="/dokter/pasien"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-users mr-2"></i> Data Pasien
                        </a>

                        <a href="{{ route('dokter.daftar_rekam_medis') }}"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-stethoscope mr-2"></i> Daftar Rekam Medis
                        </a>

                    {{-- Role: Resepsionis --}}
                    @elseif(Auth::user()->role === 'resepsionis')
                        <a href="/resepsionis" class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-home mr-2"></i> Dashboard
                        </a>

                        {{-- ✅ Menu Baru: Pasien Aktif --}}
                        <a href="{{ route('resepsionis.pasien_aktif') }}"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-users mr-2"></i> Pasien Aktif
                        </a>

                        <a href="/resepsionis/daftar-pasien"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-user-plus mr-2"></i> Daftar Offline
                        </a>
                        <a href="/resepsionis/pendaftaran"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-user-plus mr-2"></i> Pendaftaran Pasien
                        </a>

                        <a href="{{ route('resepsionis.qr_scan') }}"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-qrcode mr-2"></i> QR Scan
                        </a>

                    {{-- Role: Admin --}}
                    @elseif(Auth::user()->role === 'admin')
                        <a href="/admin" class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-users-cog mr-2"></i> Dashboard Admin
                        </a>
                        <a href="/admin/dokter"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-user-md mr-2"></i> List Dokter
                        </a>
                        <a href="/admin/pasien"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-user-md mr-2"></i> List Pasien
                        </a>
                        <a href="/admin/resepsionis"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-user-md mr-2"></i> List Resepsionis
                        </a>
                        <a href="/admin/pembayaran/create"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-credit-card mr-2"></i> Tambah Pembayaran
                        </a>
                        <a href="/admin/pembayaran/konfirmasi"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-credit-card mr-2"></i> Konfirmasi Pembayaran
                        </a>
                        <a href="/admin/pembayaran"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-credit-card mr-2"></i> Data Pembayaran
                        </a>
                        <a href="/admin/laporan"
                            class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                            <i class="fas fa-chart-line mr-2"></i> Laporan
                        </a>
                    @endif

                    {{-- Logout (punyamu memang arah ke /login — aku biarkan supaya nggak ganggu sistemmu) --}}
                    <a href="/login" class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>

                @else
                    {{-- ✅ GUEST / BELUM LOGIN --}}
                    <a href="/login" class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </a>
                @endauth

            </nav>
        </aside>

        {{-- Konten utama --}}
        <main class="flex-1 p-6 sm:p-8">
            <div class="sm:hidden flex items-center justify-between mb-5 bg-surface border border-soft rounded-2xl px-4 py-3 shadow-sm">
                <button id="sidebar-toggle" type="button" class="inline-flex items-center justify-center h-10 w-10 rounded-xl border border-soft text-secondary hover:text-primary transition">
                    <span class="sr-only">Buka menu</span>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="text-sm font-semibold text-secondary">Menu</div>
                <div class="h-10 w-10"></div>
            </div>
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('table').forEach((table) => {
                if (table.dataset.cardified === 'true') return;

                const headerCells = Array.from(table.querySelectorAll('thead th'));
                const headers = headerCells.map((th) => th.textContent.trim()).filter(Boolean);

                const bodyRows = table.tBodies.length
                    ? Array.from(table.tBodies).flatMap((tbody) => Array.from(tbody.rows))
                    : Array.from(table.querySelectorAll('tbody tr, tr'));

                bodyRows.forEach((row) => {
                    if (row.closest('thead')) return;
                    Array.from(row.children).forEach((cell, index) => {
                        if (!cell.matches('td, th')) return;
                        if (cell.dataset.label) return;
                        const label = headers[index] || `Kolom ${index + 1}`;
                        cell.dataset.label = label;
                    });
                });

                table.classList.add('responsive-table', 'theme-table');
                table.dataset.cardified = 'true';
            });

            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggle = document.getElementById('sidebar-toggle');

            const closeSidebar = () => {
                if (!sidebar || !overlay) return;
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            const openSidebar = () => {
                if (!sidebar || !overlay) return;
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };

            if (toggle) {
                toggle.addEventListener('click', () => {
                    if (sidebar && sidebar.classList.contains('-translate-x-full')) {
                        openSidebar();
                    } else {
                        closeSidebar();
                    }
                });
            }

            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            if (sidebar) {
                sidebar.querySelectorAll('a').forEach((link) => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth < 640) {
                            closeSidebar();
                        }
                    });
                });
            }

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 640) {
                    if (overlay) overlay.classList.add('hidden');
                    if (sidebar) sidebar.classList.remove('-translate-x-full');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        });
    </script>

</body>
</html>
