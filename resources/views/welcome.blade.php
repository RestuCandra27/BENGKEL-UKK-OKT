<!DOCTYPE html>
{{-- Ini adalah halaman utama (Landing Page) yang dilihat oleh pengunjung yang belum login --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        {{-- Pengaturan dasar halaman --}}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Selamat Datang - Sistem Bengkel</title>

        {{-- Memuat font kustom dari Google Fonts (via Bunny Fonts) --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        {{-- @vite: Perintah ini sangat penting. Ia menghubungkan halaman ini dengan server 'npm run dev'.
             Ini memastikan semua file CSS (termasuk Tailwind CSS) dan JavaScript terbaru dimuat. --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Style kustom untuk gambar latar belakang --}}
        <style>
            .bg-image {
                /* helper asset() membuat URL lengkap ke file gambar di dalam folder 'public/' */
                background-image: url('{{ asset('images/login-bg.jpg') }}');
                background-size: cover; /* Membuat gambar menutupi seluruh area */
                background-position: center; /* Memusatkan gambar */
            }
        </style>
    </head>
    <body class="antialiased">
        {{-- 'relative' membuat div ini menjadi acuan posisi untuk elemen di dalamnya.
             'sm:flex sm:justify-center sm:items-center' adalah class Tailwind untuk memusatkan konten di tengah layar.
             'min-h-screen' membuat div ini memiliki tinggi minimal setinggi layar. --}}
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-image">
            
            {{-- DIV ini berfungsi sebagai lapisan (overlay) gelap di atas gambar latar belakang.
                 'absolute inset-0' membuatnya menutupi seluruh area 'bg-image'.
                 'bg-black opacity-50' memberinya warna hitam dengan transparansi 50%. --}}
            <div class="absolute inset-0 bg-black opacity-50"></div>

            {{-- Logika Blade: @if (Route::has('login')) -> "Jika rute bernama 'login' ada..." --}}
            @if (Route::has('login'))
                {{-- Bagian ini untuk menampilkan tombol Login & Register di pojok kanan atas.
                     'sm:fixed sm:top-0 sm:right-0' memposisikan div ini di pojok kanan atas pada layar besar.
                     'z-10' memastikan posisinya berada di atas lapisan overlay gelap. --}}
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    
                    {{-- @auth: "Jika pengunjung SUDAH login..." --}}
                    @auth
                        {{-- Tampilkan link ke halaman dashboard --}}
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-300 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                    
                    {{-- @else: "Jika pengunjung BELUM login..." --}}
                    @else
                        {{-- Tampilkan link ke halaman login. helper route() digunakan untuk memanggil rute berdasarkan namanya. --}}
                        <a href="{{ route('login') }}" class="font-semibold text-gray-300 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                        {{-- Jika rute bernama 'register' ada... --}}
                        @if (Route::has('register'))
                            {{-- Tampilkan link ke halaman register --}}
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-300 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            {{-- Konten Utama di Tengah Halaman --}}
            {{-- 'relative z-10' juga memastikan posisinya di atas overlay gelap. --}}
            <div class="max-w-7xl mx-auto p-6 lg:p-8 text-center relative z-10">
                
                <div class="mt-8">
                   <h1 class="text-4xl md:text-6xl font-bold text-white" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">
                       Sistem Manajemen Bengkel
                   </h1>
                   <p class="mt-4 text-lg text-gray-200">
                       Solusi digital untuk manajemen bengkel yang efisien dan modern.
                   </p>
                </div>
            </div>
        </div>
    </body>
</html>

