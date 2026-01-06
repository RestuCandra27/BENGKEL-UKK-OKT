{{-- resources/views/profile/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight m-0" style="color:#e5e7eb;">
            Profil Saya
        </h2>
    </x-slot>

    @php
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Samakan dengan edit.blade.php: ambil dari semua kemungkinan kolom
        $fotoPath = $user->foto_profil
            ?? $user->foto
            ?? $user->profile_photo_path
            ?? null;

        $roleLabel = ucfirst($user->role ?? 'User');
        $joined    = $user->created_at
            ? $user->created_at->format('d M Y')
            : '-';
    @endphp

    <div class="py-4">
        <div class="max-w-4xl mx-auto">

            {{-- FLASH MESSAGE --}}
            @if (session('status') === 'profile-updated' || session('success'))
                <div class="alert alert-success mb-3">
                    {{ session('success') ?? 'Profil berhasil diperbarui.' }}
                </div>
            @endif

            <div class="card shadow-sm"
                 style="background:#020617;border-radius:16px;color:#e5e7eb;">
                <div class="card-body">

                    {{-- HEADER PROFIL: avatar + judul + tombol edit --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle overflow-hidden mr-3"
                                 style="width:88px;height:88px;
                                        border:3px solid rgba(59,130,246,.7);">
                                <img
                                    src="{{ $fotoPath
                                            ? asset('storage/'.$fotoPath)
                                            : 'https://ui-avatars.com/api/?name='
                                                . urlencode($user->nama ?? $user->email)
                                                . '&background=0f172a&color=f9fafb' }}"
                                    class="w-100 h-100"
                                    style="object-fit:cover;"
                                    alt="Foto Profil">
                            </div>

                            <div>
                                <div class="text-sm text-gray-400 mb-1">Profil</div>
                                <h3 class="mb-0" style="color:#f9fafb;">
                                    Profil Saya
                                </h3>
                            </div>
                        </div>

                        <a href="{{ route('profile.edit') }}"
                           class="btn btn-primary">
                            <i class="fas fa-pen mr-1"></i> Edit Profil
                        </a>
                    </div>

                    {{-- DATA PROFIL: "Label : Value" per baris --}}
                    <div class="mt-2" style="font-size:1rem;">

                        <p class="mb-2">
                            <span class="text-muted">Nama Lengkap :</span>
                            <span style="color:#f9fafb;"> {{ $user->nama ?? '-' }}</span>
                        </p>

                        <p class="mb-2">
                            <span class="text-muted">Email :</span>
                            <span style="color:#f9fafb;"> {{ $user->email }}</span>
                        </p>

                        <p class="mb-2">
                            <span class="text-muted">No. HP :</span>
                            <span style="color:#f9fafb;"> {{ $user->no_hp ?? '-' }}</span>
                        </p>

                        <p class="mb-2">
                            <span class="text-muted">Alamat :</span>
                            <span style="color:#f9fafb;"> {{ $user->alamat ?? '-' }}</span>
                        </p>

                        <p class="mb-2">
                            <span class="text-muted">Role :</span>
                            <span>
                                <span class="badge"
                                      style="background:#22d3ee;color:#020617;font-weight:600;">
                                    {{ $roleLabel }}
                                </span>
                            </span>
                        </p>

                        <p class="mb-0">
                            <span class="text-muted">Bergabung Sejak :</span>
                            <span style="color:#f9fafb;"> {{ $joined }}</span>
                        </p>

                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
