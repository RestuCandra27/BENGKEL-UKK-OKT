<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Menambahkan style untuk gambar latar */
            .bg-image {
                background-image: url('{{ asset('images/login-bg.jpg') }}');
                background-size: cover;
                background-position: center;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-image relative">
            <!-- Lapisan overlay gelap agar teks mudah dibaca -->
            <div class="absolute inset-0 bg-black opacity-50"></div>

            {{-- Konten form diletakkan di atas overlay --}}
            <div class="relative z-10 w-full sm:max-w-md">
                <div>
                    <a href="/">
                        {{-- Ganti logo ini dengan tulisan agar terlihat lebih baik --}}
                        <h1 class="text-4xl font-bold text-white text-center" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">
                            Bengkel UKK
                        </h1>
                    </a>
                </div>

                <div class="w-full mt-6 px-6 py-4 bg-white/75 dark:bg-gray-800/75 backdrop-blur-sm shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
