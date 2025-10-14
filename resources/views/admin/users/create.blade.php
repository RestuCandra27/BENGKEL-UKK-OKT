{{-- Menggunakan layout utama 'app.blade.php'. Semua konten di dalam sini akan dimasukkan ke dalam '{{ $slot }}' di file layout. --}}
<x-app-layout>
    {{-- Mendefinisikan konten untuk bagian 'header' di layout utama. --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pengguna Baru') }}
        </h2>
    </x-slot>

    {{-- 'card' adalah class dari AdminLTE untuk membuat kotak putih yang rapi. --}}
    <div class="card">
        <div class="card-body">

            {{-- BLOK UNTUK MENAMPILKAN ERROR VALIDASI --}}
            {{-- @if ($errors->any()): Logika Blade untuk mengecek "Apakah ada error validasi yang dikirim dari controller?" --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        {{-- @foreach: Jika ada error, lakukan perulangan dan tampilkan setiap pesan error dalam bentuk list. --}}
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            {{-- FORMULIR UTAMA --}}
            {{-- action="{{ route('admin.users.store') }}": Saat disubmit, form ini akan mengirim data ke rute yang bernama 'admin.users.store'. --}}
            {{-- method="POST": Metode pengiriman data. --}}
            <form action="{{ route('admin.users.store') }}" method="POST">
                
                {{-- @csrf: Perintah Blade yang WAJIB ada di setiap form. Ini membuat "tiket keamanan" rahasia untuk melindungi dari serangan CSRF. --}}
                @csrf

                {{-- Bagian untuk input Nama --}}
                <div class="form-group">
                    <label for="nama">Nama</label>
                    {{-- value="{{ old('nama') }}": Helper Laravel yang sangat berguna. Jika form gagal divalidasi, input ini akan otomatis terisi kembali dengan data yang sebelumnya diinput oleh user. --}}
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>

                {{-- Bagian untuk input Email --}}
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                {{-- Bagian untuk input Password --}}
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                {{-- Bagian untuk Konfirmasi Password. Laravel akan otomatis mencocokkan isinya dengan input 'password'. --}}
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                {{-- Bagian untuk memilih Role (Peran) --}}
                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" class="form-control" required>
                        <option value="admin">Admin</option>
                        <option value="montir">Montir</option>
                        <option value="kasir">Kasir</option>
                    </select>
                </div>

                {{-- Tombol untuk mengirim (submit) form --}}
                <button type="submit" class="btn btn-primary">Simpan</button>
                
                {{-- Tombol untuk batal, akan mengarahkan kembali ke halaman daftar user --}}
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</x-app-layout>

