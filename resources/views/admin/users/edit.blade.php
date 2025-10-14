{{-- Menggunakan layout utama 'app.blade.php'. Semua konten di dalam sini akan dimasukkan ke dalam '{{ $slot }}'. --}}
<x-app-layout>
    {{-- Mendefinisikan konten untuk bagian 'header' di layout utama. --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengguna') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            {{-- BLOK UNTUK MENAMPILKAN ERROR VALIDASI (Sama seperti di form Create) --}}
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
            {{-- PERBEDAAN 1: Form Action. URL-nya menunjuk ke rute 'admin.users.update' dan menyertakan ID user yang akan di-update. --}}
            <form action="{{ route('admin.users.update', $user->id_user) }}" method="POST">
                @csrf
                {{-- PERBEDAAN 2: @method('PATCH'). Karena HTML form hanya mendukung GET dan POST, kita perlu "memberitahu" Laravel bahwa niat kita sebenarnya adalah untuk melakukan UPDATE (dengan method PATCH). --}}
                @method('PATCH')
                
                <div class="form-group">
                    <label for="nama">Nama</label>
                    {{-- PERBEDAAN 3: value. `old('nama', $user->nama)` artinya: "Gunakan data input lama ('nama') jika ada error validasi, jika tidak, gunakan data asli dari database ($user->nama)". Ini untuk mengisi form dengan data yang sudah ada. --}}
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" class="form-control" required>
                        {{-- PERBEDAAN 4: Logika 'selected'. Logika ini akan otomatis memilih opsi yang sesuai dengan role user saat ini. --}}
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="montir" {{ $user->role == 'montir' ? 'selected' : '' }}>Montir</option>
                        <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                    </select>
                </div>
                <hr>
                {{-- PERBEDAAN 5: Penanganan Password. Kita tidak mewajibkan input password karena user mungkin hanya ingin mengubah nama atau email. --}}
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

