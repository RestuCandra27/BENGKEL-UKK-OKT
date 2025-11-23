<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    {{-- KODE CSS KUSTOM (DARK MODE) --}}
    <style>
        /* Perbaikan untuk body agar tidak ada "batang putih" */
        body,
        .content-wrapper,
        .main-footer,
        .main-header,
        .card {
            background-color: #1f2937 !important;
            color: #d1d5db !important;
        }

        .main-header,
        .card-header {
            border-bottom: 1px solid #4b5563 !important;
        }

        .content-header h1,
        .card-title,
        .card-body h3,
        .content-wrapper .h2,
        .leading-tight {
            color: #f9fafb !important;
        }

        .table {
            color: #d1d5db !important;
        }

        .table-bordered th,
        .table-bordered td {
            border-color: #4b5563 !important;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(55, 65, 81, 0.5) !important;
        }

        /* Perbaikan untuk table hover (agar teks tidak hilang) */
        .table-hover tbody tr:hover {
            background-color: #374151 !important;
            /* Warna abu-abu yang lebih gelap */
            color: #f9fafb !important;
            /* Paksa teks tetap putih */
        }

        .main-sidebar {
            background-color: #111827 !important;
        }

        .nav-sidebar .nav-item .nav-link.active {
            background-color: #3b82f6 !important;
            color: #fff !important;
        }

        .nav-sidebar .nav-item .nav-link,
        .nav-header {
            color: #9ca3af !important;
        }

        .nav-sidebar .nav-item .nav-link:hover {
            background-color: #374151 !important;
        }

        .brand-link .brand-text {
            color: #fff !important;
        }

        .navbar-nav .nav-link {
            color: #d1d5db !important;
        }

        .small-box {
            background-color: #374151;
            color: #f9fafb;
        }

        .small-box .icon>i {
            color: rgba(255, 255, 255, 0.3);
        }

        .small-box-footer {
            background-color: rgba(0, 0, 0, 0.2);
        }
    </style>
    {{-- AKHIR KODE CSS KUSTOM --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        {{-- NAVIGASI ATAS (NAVBAR) --}}
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        {{ Auth::user()->nama }} <i class="far fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
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
        </nav>

        {{-- MENU KIRI (SIDEBAR) --}}
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ route('dashboard') }}" class="brand-link">
                <span class="brand-text font-weight-light">Bengkel UKK</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                        {{-- MENU DASHBOARD --}}
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        {{-- MENU MANAJEMEN USER --}}
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}"
                                class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>Manajemen User</p>
                            </a>
                        </li>

                        {{-- GRUP MENU DATA MASTER --}}
                        <li class="nav-header">DATA MASTER</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.pelanggan.index') }}"
                                class="nav-link {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-address-book"></i>
                                <p>Data Pelanggan</p>
                            </a>
                        </li>

                        {{-- =============================================== --}}
                        {{-- PERBAIKAN: MENU BARU DITAMBAHKAN DI SINI --}}
                        {{-- =============================================== --}}

                        <li class="nav-item">
                            {{-- PERBAIKAN: Mengarahkan ke rute yang benar --}}
                            <a href="{{ route('admin.layanans.index') }}" class="nav-link {{ request()->routeIs('admin.layanans.*') ? 'active' : '' }}">
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

                        <li class="nav-item">
                            <a href="{{ route('admin.spareparts.index') }}" class="nav-link {{ request()->routeIs('admin.spareparts.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>Data Sparepart</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.kendaraans.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.kendaraans.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-motorcycle"></i>
                                <p>Data Kendaraan</p>
                            </a>
                        </li>

                        <li class="nav-header">INVENTORI & STOK</li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.pembelian-spareparts.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.pembelian-spareparts.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-boxes"></i>
                                <p>Stok Masuk (Pembelian)</p>
                            </a>
                        </li>
                        <li class="nav-header">TRANSAKSI BENGKEL</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.servis.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.servis.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tools"></i>
                                <p>Pendaftaran Servis</p>
                            </a>
                        </li>

                        

                        {{-- =============================================== --}}
                        {{-- AKHIR DARI MENU BARU --}}
                        {{-- =============================================== --}}

                    </ul>
                </nav>
            </div>
        </aside>

        {{-- AREA KONTEN UTAMA --}}
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
                <div class="container-fluid pt-3">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <!-- {{-- FOOTER (BAGIAN PALING BAWAH) --}}
        <footer class="main-footer">
            <strong>Copyright &copy; 2024 <a href="#">Restu Candra Novianto</a>.</strong> All rights reserved.
        </footer> -->
    </div>
    {{-- Akhir dari div.wrapper --}}

    {{-- SKRIP JAVASCRIPT --}}
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>

</html>