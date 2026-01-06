<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bengkel Candra') }}</title>

    {{-- FONT --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    {{-- ADMINLTE + ICON --}}
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    {{-- THEME CANDRA GARAGE (DARK + NEON CYAN/PURPLE) --}}
    <style>
        :root {
            --bg-body: #020617;
            --bg-wrapper: #020617;
            --bg-sidebar: #020617;
            --bg-card: #0f172a;
            --bg-card-soft: #111827;
            --accent: #22d3ee;
            --accent-2: #a855f7;
            --text-main: #e5e7eb;
            --text-muted: #9ca3af;
            --border-soft: rgba(148, 163, 184, 0.25);
        }

        /* ====== BASE LAYOUT ====== */

        body {
            background: radial-gradient(circle at top, #1f2937 0, #020617 40%, #020617 100%);
            color: var(--text-main);
        }

        .content-wrapper {
            background: radial-gradient(circle at top, #020617 0, #020617 55%, #020617 100%) !important;
            color: var(--text-main);
        }

        .main-header {
            background: linear-gradient(to right, #020617, #020617) !important;
            border-bottom: 1px solid rgba(148, 163, 184, 0.25) !important;
            color: var(--text-main) !important;
        }

        .navbar-nav .nav-link {
            color: var(--text-main) !important;
        }

        .main-sidebar {
            background: radial-gradient(circle at top, #020617, #020617 55%, #020617 100%) !important;
        }

        .brand-link {
            background: transparent !important;
            border-bottom: 1px solid rgba(148, 163, 184, 0.2) !important;
        }

        .brand-link .brand-text {
            color: #f9fafb !important;
            font-weight: 600;
            letter-spacing: .08em;
        }

        /* ====== SIDEBAR MENU ====== */

        .nav-sidebar .nav-item .nav-link {
            color: var(--text-muted) !important;
            border-radius: .5rem;
            margin: 2px 6px;
        }

        .nav-sidebar .nav-item .nav-link:hover {
            background: rgba(15, 23, 42, 0.9) !important;
            color: var(--text-main) !important;
        }

        .nav-sidebar .nav-item .nav-link.active {
            background: linear-gradient(135deg, var(--accent), var(--accent-2)) !important;
            color: #fff !important;
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.4);
        }

        .nav-header {
            color: #6b7280 !important;
            font-size: .75rem;
            letter-spacing: .16em;
            text-transform: uppercase;
            margin-top: .75rem;
        }

        /* ====== CARD & SMALL BOX ====== */

        .card,
        .small-box {
            background: radial-gradient(circle at top left, rgba(148, 163, 184, 0.14), var(--bg-card-soft)) !important;
            border-radius: 1rem !important;
            border: 1px solid var(--border-soft) !important;
            color: var(--text-main) !important;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.55);
        }

        .card-header {
            background: transparent !important;
            border-bottom: 1px solid rgba(148, 163, 184, 0.16) !important;
        }

        /* ====== PAGE TITLE (HEADER SETIAP HALAMAN) ====== */
        .content-header h1,
        .content-header h2,
        .content-header .h1,
        .content-header .h2,
        .page-title-main {
            color: #f9fafb !important;
            font-weight: 700;
            letter-spacing: .02em;
        }

        /* garis kecil di bawah judul biar lebih tegas */
        .page-title-main::after,
        .content-header h1::after,
        .content-header h2::after {
            content: "";
            display: block;
            width: 80px;
            height: 2px;
            margin-top: .3rem;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--accent), var(--accent-2));
        }

        .card-title {
            color: #f9fafb !important;
            font-weight: 600;
        }

        .card-body {
            color: var(--text-main) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        /* SMALL BOX COLOR */
        .small-box.bg-info,
        .small-box.bg-success,
        .small-box.bg-warning,
        .small-box.bg-danger,
        .small-box {
            background: radial-gradient(circle at top left,
                    rgba(56, 189, 248, 0.30),
                    var(--bg-card-soft)) !important;
            color: #f9fafb !important;
        }

        .small-box .inner h3 {
            font-weight: 700;
        }

        .small-box .icon>i {
            color: rgba(255, 255, 255, 0.2) !important;
        }

        .small-box-footer {
            background: rgba(15, 23, 42, 0.85) !important;
            border-radius: 0 0 1rem 1rem;
            border-top: 1px solid rgba(148, 163, 184, 0.3);
            color: var(--text-main) !important;
        }

        /* ====== TABLE ====== */

        .table {
            color: var(--text-main) !important;
        }

        .table-bordered th,
        .table-bordered td {
            border-color: rgba(55, 65, 81, 0.9) !important;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(31, 41, 55, 0.8) !important;
        }

        .table-hover tbody tr:hover {
            background-color: #1f2937 !important;
            color: #f9fafb !important;
        }

        /* ====== CONTENT HEADER ====== */

        .content-header {
            border-bottom: none;
            padding-bottom: .5rem;
        }

        .content-header .lead,
        .content-header p {
            color: var(--text-muted) !important;
        }
    </style>


    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        {{-- NAVBAR ATAS --}}
        <nav class="main-header navbar navbar-expand navbar-dark">
            {{-- Left --}}
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>

            {{-- Right --}}
            <ul class="navbar-nav ml-auto">
                <ul class="navbar-nav ml-auto">
                    @php
                    $user = Auth::user();
                    $photoUrl = $user->profile_photo_path
                    ? asset('storage/' . $user->profile_photo_path)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama ?? $user->name) . '&background=111827&color=f9fafb';
                    @endphp

                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" style="gap:.5rem;">
                            <img src="{{ $photoUrl }}"
                                alt="Foto Profil"
                                style="width:28px;height:28px;border-radius:999px;object-fit:cover;border:2px solid rgba(255,255,255,0.8);">

                            <span>{{ $user->nama ?? $user->name }}</span>
                            <i class="fas fa-chevron-down" style="font-size:.7rem;"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <a href="{{ route('profile.show') }}" class="dropdown-item">
                                Profil Saya
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" class="dropdown-item"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Logout
                                </a>
                            </form>
                        </div>
                    </li>
                </ul>

            </ul>
        </nav>

        {{-- SIDEBAR --}}
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ route('dashboard') }}" class="brand-link">
                <span class="brand-text font-weight-light">Candra Garage</span>
            </a>

            <div class="sidebar">
                <nav class="mt-2">
                    @php
                    $role = auth()->user()->role ?? null;

                    // Sparepart group
                    $isSparepartMenuActive =
                    request()->routeIs('admin.spareparts.*') ||
                    request()->routeIs('admin.stok-masuk.*');

                    // 🔹 GROUP TRANSAKSI SERVIS
                    $isTransaksiMenuActive =
                    request()->routeIs('admin.servis.*') ||
                    request()->routeIs('admin.reservasis.*');

                    // grup Invoice & Pembayaran
                    $isInvoiceMenuActive =
                    request()->routeIs('admin.invoices.*') ||
                    request()->routeIs('admin.payments.*');
                    @endphp


                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        {{-- DASHBOARD (SEMUA ROLE KECUALI PELANGGAN) --}}
                        @if (auth()->check() && auth()->user()->role !== 'pelanggan')
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        @endif

                        {{-- ===================== --}}
                        {{-- MENU ADMIN            --}}
                        {{-- ===================== --}}
                        @if ($role === 'admin')
                        <li class="nav-header">Manajemen User</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}"
                                class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>Manajemen User</p>
                            </a>
                        </li>

                        <li class="nav-header">Data Master</li>

                        <li class="nav-item">
                            <a href="{{ route('admin.pelanggan.index') }}"
                                class="nav-link {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-address-book"></i>
                                <p>Data Pelanggan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.layanans.index') }}"
                                class="nav-link {{ request()->routeIs('admin.layanans.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-concierge-bell"></i>
                                <p>Data Layanan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.paket-servis.index') }}"
                                class="nav-link {{ request()->routeIs('admin.paket-servis.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-box-open"></i>
                                <p>Data Paket Servis</p>
                            </a>
                        </li>

                        <li class="nav-item {{ $isSparepartMenuActive ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ $isSparepartMenuActive ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    Sparepart & Stok
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.spareparts.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.spareparts.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Sparepart</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.stok-masuk.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.stok-masuk.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Stok Masuk</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.kendaraans.index') }}"
                                class="nav-link {{ request()->routeIs('admin.kendaraans.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-motorcycle"></i>
                                <p>Data Kendaraan</p>
                            </a>
                        </li>

                        <li class="nav-header">Transaksi Bengkel</li>

                        {{-- 🔹 Grup: Servis & Reservasi --}}
                        <li class="nav-item {{ $isTransaksiMenuActive ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ $isTransaksiMenuActive ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tools"></i>
                                <p>
                                    Servis & Reservasi
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                                {{-- Pendaftaran Servis --}}
                                <li class="nav-item">
                                    <a href="{{ route('admin.servis.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.servis.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pendaftaran Servis</p>
                                    </a>
                                </li>

                                {{-- Reservasi Servis --}}
                                <li class="nav-item">
                                    <a href="{{ route('admin.reservasis.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.reservasis.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Reservasi Servis</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- INVOICE & PEMBAYARAN --}}
                        <li class="nav-item {{ $isInvoiceMenuActive ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ $isInvoiceMenuActive ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p>
                                    Invoice & Pembayaran
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                {{-- Data Invoice --}}
                                <li class="nav-item">
                                    <a href="{{ route('admin.invoices.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Invoice</p>
                                    </a>
                                </li>

                                {{-- Pembayaran (verifikasi) --}}
                                <li class="nav-item">
                                    <a href="{{ route('admin.payments.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pembayaran</p>
                                    </a>
                                </li>
                            </ul>
                        </li>



                        <li class="nav-header">Monitoring</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.log-aktivitas.index') }}"
                                class="nav-link {{ request()->routeIs('admin.log-aktivitas.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>Log Aktivitas</p>
                            </a>
                        </li>
                        @endif

                        {{-- MENU KASIR --}}
                        @if ($role === 'kasir')
                        <li class="nav-header">Transaksi</li>
                        <li class="nav-item">
                            <a href="{{ route('kasir.invoices.index') }}"
                                class="nav-link {{ request()->routeIs('kasir.invoices.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p>Invoice & Pembayaran</p>
                            </a>
                        </li>
                        @endif

                        {{-- MENU PELANGGAN --}}
                        @if ($role === 'pelanggan')
                        <li class="nav-header">Servis Saya</li>

                        <li class="nav-item">
                            <a href="{{ route('pelanggan.dashboard') }}"
                                class="nav-link {{ request()->routeIs('pelanggan.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Dashboard Pelanggan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('pelanggan.servis.index') }}"
                                class="nav-link {{ request()->routeIs('pelanggan.servis.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Riwayat Servis</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('pelanggan.invoices.index') }}"
                                class="nav-link {{ request()->routeIs('pelanggan.invoices.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p>Invoice Saya</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('pelanggan.reservasis.index') }}"
                                class="nav-link {{ request()->routeIs('pelanggan.reservasis.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar-check"></i>
                                <p>Booking Servis</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('pelanggan.kendaraans.index') }}"
                                class="nav-link {{ request()->routeIs('pelanggan.kendaraans.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-car"></i>
                                <p>Kendaraan Saya</p>
                            </a>
                        </li>
                        @endif

                        {{-- MENU MONTIR --}}
                        @if ($role === 'montir')
                        <li class="nav-header">Servis</li>
                        <li class="nav-item">
                            <a href="{{ route('montir.servis.index') }}"
                                class="nav-link {{ request()->routeIs('montir.servis.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-wrench"></i>
                                <p>Servis Saya</p>
                            </a>
                        </li>
                        @endif

                    </ul>
                </nav>
            </div>
        </aside>

        {{-- CONTENT --}}
        <div class="content-wrapper">
            @if (isset($header))
            <header>
                <div class="content-header">
                    <div class="container-fluid">
                        {{ $header }}
                    </div>
                </div>
            </header>
            @endif

            <main class="content">
                <div class="container-fluid pt-3 pb-3">
                    {{ $slot }}
                </div>
            </main>
        </div>

        {{-- FOOTER (opsional kalau mau diaktifkan nanti) --}}
        {{--
    <footer class="main-footer">
        <strong>&copy; {{ date('Y') }} Candra Garage.</strong> Semua hak dilindungi.
        </footer>
        --}}
    </div>

    {{-- JS --}}
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>

</html>