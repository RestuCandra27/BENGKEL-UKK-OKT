{{-- resources/views/profile/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight m-0" style="color:#e5e7eb;">
            Edit Profil
        </h2>
    </x-slot>

    @php
        /** @var \App\Models\User $user */
        $user = $user ?? auth()->user();

        // ambil foto dari semua kemungkinan kolom
        $fotoPath = $user->foto_profil
            ?? $user->foto
            ?? $user->profile_photo_path
            ?? null;
    @endphp

    <div class="py-4">
        <div class="max-w-3xl mx-auto">

            {{-- ERROR GLOBAL --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    Terjadi kesalahan, periksa kembali form di bawah.
                </div>
            @endif

            <div class="card shadow-sm"
                 style="background:linear-gradient(145deg,#0b1223,#020617);border-radius:16px;color:#e5e7eb;">

                <div class="card-body">

                    <form method="POST" 
                          action="{{ route('profile.update') }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        {{-- HEADER FOTO + TITLE --}}
                        <div class="d-flex align-items-center mb-4">

                            <div class="rounded-circle overflow-hidden mr-4"
                                 style="width:96px;height:96px;border:3px solid rgba(96,165,250,.7);">
                                <img
                                    src="{{ $fotoPath
                                            ? asset('storage/'.$fotoPath)
                                            : 'https://ui-avatars.com/api/?name='.urlencode($user->nama ?? $user->email).'&background=0f172a&color=f9fafb' }}"
                                    alt="Foto Profil"
                                    class="w-100 h-100"
                                    style="object-fit:cover;">
                            </div>

                            <div>
                                <h4 class="mb-1 text-white">Ganti Foto Profil</h4>
                                <small class="text-muted">
                                    Format JPG/PNG • Maks 2 MB
                                </small>
                            </div>

                        </div>


                        {{-- INPUT FOTO --}}
                        <div class="form-group mb-4">
                            <input type="file"
                                   name="foto_profil"
                                   class="form-control-file @error('foto_profil') is-invalid @enderror"
                                   accept=".jpg,.jpeg,.png">

                            @error('foto_profil')
                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>


                        {{-- NAMA --}}
                        <div class="form-group mb-3">
                            <label for="name">Nama Lengkap</label>
                            <input id="name"
                                   type="text"
                                   name="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama', $user->nama) }}"
                                   required>

                            @error('nama')
                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>


                        {{-- EMAIL --}}
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input id="email"
                                   type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   required>

                            @error('email')
                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>


                        {{-- NO HP --}}
                        <div class="form-group mb-3">
                            <label for="no_hp">No. HP</label>
                            <input id="no_hp"
                                   type="text"
                                   name="no_hp"
                                   class="form-control @error('no_hp') is-invalid @enderror"
                                   value="{{ old('no_hp', $user->no_hp) }}">

                            @error('no_hp')
                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>


                        {{-- ALAMAT --}}
                        <div class="form-group mb-4">
                            <label for="alamat">Alamat</label>
                            <textarea id="alamat"
                                      name="alamat"
                                      rows="2"
                                      class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $user->alamat) }}</textarea>

                            @error('alamat')
                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>


                        {{-- TOMBOL --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('profile.show') }}"
                               class="btn btn-secondary">
                                &laquo; Kembali
                            </a>

                            <button type="submit"
                                    class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
