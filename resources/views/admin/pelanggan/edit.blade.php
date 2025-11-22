{{-- Menggunakan layout utama 'app.blade.php'. --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Pelanggan') }}
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
            {{-- Mengarah ke rute update, mengirimkan ID pelanggan --}}
            <form action="{{ route('admin.pelanggan.update', $pelanggan->id) }}" method="POST">
                @csrf
                @method('PATCH') {{-- Method 'PATCH' untuk update --}}
                
                {{-- Input Nama --}}
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $pelanggan->nama) }}" required>
                </div>

                {{-- Input Email --}}
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $pelanggan->email) }}" required>
                </div>

                {{-- Input No. HP (Spesifik Pelanggan) --}}
                <div class="form-group">
                    <label for="no_hp">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pelanggan->no_hp) }}">
                </div>

                {{-- Input Alamat (Spesifik Pelanggan) --}}
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" class="form-control">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                </div>

                {{-- Input Jenis Member (Spesifik Pelanggan) --}}
                <div class="form-group">
                    <label for="jenis_member">Jenis Member</label>
                    <select name="jenis_member" class="form-control" required>
                        <option value="Reguler" {{ old('jenis_member', $pelanggan->jenis_member) == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="VIP" {{ old('jenis_member', $pelanggan->jenis_member) == 'VIP' ? 'selected' : '' }}>VIP</option>
                        <option value="Fleet" {{ old('jenis_member', $pelanggan->jenis_member) == 'Fleet' ? 'selected' : '' }}>Fleet</option>
                    </select>
                </div>

                <hr>
                <p class="text-muted">Kosongkan password jika tidak ingin mengubahnya.</p>
                
                {{-- Input Password (Opsional) --}}
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</x-app-layout>