{{-- Menggunakan layout utama 'app.blade.php'. --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengguna') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            {{-- FORMULIR UTAMA --}}
            
            {{-- PERBAIKAN 1: Menambahkan x-data (untuk form "pintar") --}}
            {{-- Nilai default-nya adalah role user yang sedang diedit --}}
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" 
                  x-data="{ selectedRole: '{{ old('role', $user->role) }}' }">
                
                @csrf
                @method('PATCH')
                
                <div class="form-group">
                    <label for="nama">Nama</label>
                    {{-- Mengisi value dengan data lama (jika validasi gagal) or data asli --}}
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    {{-- PERBAIKAN 2: Menambahkan x-model dan logika 'selected' yang lebih baik --}}
                    <select name="role" class="form-control" required x-model="selectedRole">
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="montir" {{ old('role', $user->role) == 'montir' ? 'selected' : '' }}>Montir</option>
                        <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                    </select>
                </div>

                {{-- PERBAIKAN 3: Menambahkan input Kode Montir yang dinamis (sama seperti di create) --}}
                <div class="form-group" x-show="selectedRole === 'montir'" style="display: none;">
                    <label for="kode_montir">Kode Montir</label>
                    {{-- Mengisi value dengan kode montir yang sudah ada --}}
                    <input type="text" name="kode_montir" class="form-control" value="{{ old('kode_montir', $user->kode_montir) }}">
                </div>

                <hr>
                <p class="text-muted">Kosongkan password jika tidak ingin mengubahnya.</p>
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</x-app-layout>