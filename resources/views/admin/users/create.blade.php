<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pengguna Baru') }}
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
            
            {{-- Ini adalah form "pintar" yang menggunakan Alpine.js (x-data) --}}
            <form action="{{ route('admin.users.store') }}" method="POST" 
                  x-data="{ selectedRole: '{{ old('role', 'admin') }}' }">
                
                @csrf

                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    {{-- x-model akan memberitahu JavaScript role apa yang dipilih --}}
                    <select name="role" class="form-control" required x-model="selectedRole">
                        <option value="admin">Admin</option>
                        <option value="montir">Montir</option>
                        <option value="kasir">Kasir</option>
                    </select>
                </div>

                {{-- x-show akan menampilkan ini HANYA jika selectedRole == 'montir' --}}
                {{-- style="display: none;" mencegah "batang putih" (FOUC) --}}
                <div class="form-group" x-show="selectedRole === 'montir'" style="display: none;">
                    <label for="kode_montir">Kode Montir</label>
                    <input type="text" name="kode_montir" class="form-control" value="{{ old('kode_montir') }}">
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</x-app-layout>