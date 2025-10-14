<!DOCTYPE html>
{{-- Ini adalah file "cangkang" atau master layout untuk semua halaman setelah user login. --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- Pengaturan dasar untuk halaman web --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Token CSRF: "Tiket keamanan" rahasia dari Laravel untuk melindungi setiap form --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Judul halaman, diambil dari nama aplikasi di file .env --}}
    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Memuat Aset CSS dari luar --}}
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons (untuk ikon) -->
    {{-- helper asset() membuat URL lengkap ke file di dalam folder 'public/' --}}
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style (file CSS utama dari AdminLTE) -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    {{-- KODE CSS KUSTOM: Blok ini kita tambahkan untuk mengubah tema AdminLTE menjadi gelap --}}
    <style>
        /* Mengubah latar belakang utama dan warna teksnya */
        .content-wrapper, .main-footer, .main-header, .card {
            background-color: #1f2937 !important; /* Warna abu-abu gelap */
            color: #d1d5db !important; /* Warna teks putih keabuan */
        }
        .main-header, .card-header {
            border-bottom: 1px solid #4b5563 !important; /* Garis pemisah */
        }
        /* Mengubah warna teks judul halaman agar tetap putih */
        .content-header h1, .card-title, .card-body h3, .content-wrapper .h2, .leading-tight {
            color: #f9fafb !important;
        }
        /* Mengubah warna teks dan border tabel */
        .table { color: #d1d5db !important; }
        .table-bordered th, .table-bordered td { border-color: #4b5563 !important; }
        .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(55, 65, 81, 0.5) !important; }
        /* Mengubah warna sidebar kiri */
        .main-sidebar { background-color: #111827 !important; }
        /* Mengubah warna menu yang sedang aktif di sidebar */
        .nav-sidebar .nav-item .nav-link.active {
            background-color: #3b82f6 !important; /* Warna biru terang sebagai aksen */
            color: #fff !important;
        }
        /* Mengubah warna menu lain di sidebar */
        .nav-sidebar .nav-item .nav-link, .nav-header { color: #9ca3af !important; }
        .nav-sidebar .nav-item .nav-link:hover { background-color: #374151 !important; }
        /* Mengubah warna teks logo */
        .brand-link .brand-text { color: #fff !important; }
        /* Mengubah warna teks di navbar atas */
        .navbar-nav .nav-link { color: #d1d5db !important; }
        /* Mengubah warna kartu di dashboard */
        .small-box { background-color: #374151; color: #f9fafb; }
        .small-box .icon > i { color: rgba(255, 255, 255, 0.3); }
        .small-box-footer { background-color: rgba(0, 0, 0, 0.2); }
    </style>
    {{-- AKHIR KODE CSS KUSTOM --}}

    {{-- @vite: Perintah dari Breeze untuk memuat file CSS & JS yang sudah di-compile oleh 'npm run dev' --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini">
    {{-- 'wrapper' adalah pembungkus utama untuk seluruh layout AdminLTE --}}
    <div class="wrapper">

        {{-- NAVIGASI ATAS (NAVBAR) --}}
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            {{-- Bagian kiri navbar --}}
            <ul class="navbar-nav">
                <li class="nav-item">
                    {{-- Tombol "hamburger" untuk membuka/menutup sidebar, fungsinya diatur oleh JS AdminLTE --}}
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            {{-- Bagian kanan navbar --}}
            <ul class="navbar-nav ml-auto">
                {{-- Dropdown untuk profil user --}}
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        {{-- Menampilkan nama dari user yang sedang login saat ini --}}
                        {{ Auth::user()->nama }} <i class="far fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        {{-- Link ke halaman profil, dibuat menggunakan nama rute --}}
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            Profil Saya
                        </a>
                        <div class="dropdown-divider"></div>
                        {{-- Form untuk proses logout --}}
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
            {{-- Link logo di pojok kiri atas --}}
            <a href="{{ route('dashboard') }}" class="brand-link">
                <span class="brand-text font-weight-light">Bengkel UKK</span>
            </a>
            <div class="sidebar">
                {{-- Bagian menu navigasi --}}
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        
                        {{-- MENU DASHBOARD --}}
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                {{-- Logika Blade: Jika rute saat ini adalah 'dashboard', tambahkan class 'active' (warna biru) --}}
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        {{-- MENU MANAJEMEN USER --}}
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}"
                                {{-- Logika Blade: Jika rute saat ini diawali dengan 'admin.users.', menu akan aktif --}}
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
                    </ul>
                </nav>
            </div>
        </aside>

        {{-- AREA KONTEN UTAMA --}}
        <div class="content-wrapper">
            {{-- Bagian untuk menampilkan judul halaman (misal: "Dashboard Bengkel") --}}
            @if (isset($header))
                <header>
                    <div class="content-header">
                        <div class="container-fluid">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endif

            {{-- BAGIAN PALING PENTING --}}
            {{-- {{ $slot }} adalah "lubang" di mana semua konten dari view lain (dashboard.blade.php, dll) akan dimasukkan --}}
            <main class="content">
                <div class="container-fluid pt-3">
                    {{ $slot }}
                </div>
            </main>
        </div>

        {{-- FOOTER (BAGIAN PALING BAWAH) --}}
        <footer class="main-footer">
            <strong>Copyright &copy; 2024 <a href="#">Restu Candra Novianto</a>.</strong> All rights reserved.
        </footer>
    </div>
    {{-- Akhir dari div.wrapper --}}

    {{-- SKRIP JAVASCRIPT --}}
    {{-- Diletakkan di akhir agar halaman dimuat lebih cepat --}}
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>

</html>

