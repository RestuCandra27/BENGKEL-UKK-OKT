<nav x-data="{ open: false }" class="bg-dark border-b border-secondary">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Left Side (Logo) -->
            <div class="flex">
                <a href="{{ route('dashboard') }}" class="text-white text-lg font-bold">
                    Bengkel UKK
                </a>
            </div>

            <!-- Right Side (User Profile Popup) -->
            <div class="hidden sm:flex sm:items-center">
                @php
                $user = Auth::user();
                $initial = strtoupper(mb_substr($user->nama ?? $user->email, 0, 1));
                @endphp

                <div class="ml-3 relative" x-data="{ open: false }">
                    {{-- Tombol kecil: avatar bulat + nama --}}
                    <button @click="open = !open"
                        class="flex items-center space-x-2 focus:outline-none">
                        <div class="h-9 w-9 rounded-full bg-gray-500 flex items-center justify-center text-white font-semibold">
                            {{ $initial }}
                        </div>
                        <div class="text-left leading-tight">
                            <div class="text-sm text-white font-semibold">
                                {{ $user->nama ?? $user->email }}
                            </div>
                            <div class="text-xs text-gray-300">
                                {{ ucfirst($user->role ?? 'user') }}
                            </div>
                        </div>
                    </button>

                    {{-- POPUP PROFIL MODERN --}}
                    <div x-show="open"
                        @click.away="open = false"
                        x-transition
                        class="absolute right-0 mt-3 w-72 bg-white rounded-xl shadow-xl border border-gray-200 z-50">

                        {{-- Bagian atas: avatar besar + info --}}
                        <div class="px-4 py-3 border-b border-gray-200 flex items-center space-x-3">
                            <div class="h-12 w-12 rounded-full bg-gray-600 flex items-center justify-center text-white text-lg font-bold">
                                {{ $initial }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">
                                    {{ $user->nama ?? $user->email }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $user->email }}
                                </div>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs
                                 bg-gray-100 text-gray-700">
                                    {{ ucfirst($user->role ?? 'user') }}
                                </span>
                            </div>
                        </div>

                        {{-- Menu khusus per role --}}
                        <div class="px-4 py-2 text-xs text-gray-400 uppercase tracking-wide">
                            Navigasi Cepat
                        </div>
                        <div class="px-2 pb-2">
                            @if ($user->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="flex items-center px-2 py-1.5 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                                Dashboard Admin
                            </a>
                            @elseif ($user->role === 'kasir')
                            <a href="{{ route('kasir.dashboard') }}"
                                class="flex items-center px-2 py-1.5 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                                Dashboard Kasir
                            </a>
                            @elseif ($user->role === 'montir')
                            <a href="{{ route('montir.dashboard') }}"
                                class="flex items-center px-2 py-1.5 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                                Dashboard Montir
                            </a>
                            @elseif ($user->role === 'pelanggan')
                            <a href="{{ route('pelanggan.dashboard') }}"
                                class="flex items-center px-2 py-1.5 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                                Dashboard Pelanggan
                            </a>
                            @endif

                            {{-- Link ke halaman profil --}}
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center px-2 py-1.5 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                                Profil Saya
                            </a>
                        </div>

                        <div class="px-4 py-2 text-xs text-gray-400 uppercase tracking-wide border-t border-gray-200">
                            Akun
                        </div>
                        <div class="px-2 pb-3">
                            {{-- Link langsung ke bagian password di halaman profil (anchor #password) --}}
                            <a href="{{ route('profile.edit') }}#password"
                                class="flex items-center px-2 py-1.5 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                                Ganti Password
                            </a>

                            {{-- Logout --}}
                            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left flex items-center px-2 py-1.5 text-sm rounded-md text-red-600 hover:bg-red-50">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Mobile Menu Button -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="text-gray-300 hover:text-white focus:outline-none focus:text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

        </div>
    </div>
</nav>