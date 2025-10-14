    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Pelanggan') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->nama }}!</h3>
                        <p class="mt-2">Ini adalah halaman dashboard Anda. Di sini Anda bisa melihat riwayat servis dan informasi lainnya.</p>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
    
