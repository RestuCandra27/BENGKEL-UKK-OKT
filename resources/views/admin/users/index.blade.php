{{-- Menggunakan layout utama 'app.blade.php'. Semua konten di dalam sini akan dimasukkan ke dalam '{{ $slot }}' di file layout. --}}
<x-app-layout>
    {{-- Mendefinisikan konten untuk bagian 'header' di layout utama. Ini akan menjadi judul halaman. --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    {{-- BLOK UNTUK MENAMPILKAN PESAN SUKSES ATAU ERROR --}}
    {{-- @if (session('success')): Logika Blade untuk mengecek "Apakah ada pesan 'success' yang dikirim dari controller (misalnya setelah berhasil menambah/mengedit/menghapus data)?" --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{-- Menampilkan isi pesan 'success' --}}
            {{ session('success') }}
        </div>
    @endif
     @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- 'card' adalah class dari AdminLTE untuk membuat kotak konten utama. --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pengguna (Admin, Montir, Kasir)</h3>
            {{-- 'card-tools' adalah class AdminLTE untuk menempatkan elemen (seperti tombol) di pojok kanan atas card-header. --}}
            <div class="card-tools">
                {{-- Tombol ini mengarah ke rute yang bernama 'admin.users.create' untuk menampilkan form tambah user. --}}
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah User
                </a>
            </div>
        </div>
        <div class="card-body">
            {{-- Class 'table', 'table-bordered', 'table-striped' adalah dari Bootstrap/AdminLTE untuk membuat tabel terlihat bagus. --}}
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach: Perintah Blade untuk melakukan perulangan. Ia akan mengulang untuk setiap item di dalam variabel '$users' yang dikirim dari UserController. --}}
                    @foreach ($users as $user)
                    <tr>
                        {{-- '$loop->iteration' adalah variabel khusus Blade yang otomatis menghasilkan nomor urut (1, 2, 3, ...) untuk setiap perulangan. --}}
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->email }}</td>
                        {{-- 'badge' dan 'bg-success' adalah class dari AdminLTE untuk membuat label 'role' terlihat lebih menarik. --}}
                        <td><span class="badge bg-success">{{ $user->role }}</span></td>
                        <td>
                            {{-- Tombol Edit: Mengarah ke rute 'admin.users.edit' sambil membawa ID user yang dipilih ($user->id_user). --}}
                            <a href="{{ route('admin.users.edit', $user->id_user) }}" class="btn btn-sm btn-info">Edit</a>
                            
                            {{-- Tombol Hapus: Dibuat menggunakan form untuk keamanan dan agar bisa mengirim method 'DELETE'. --}}
                            {{-- 'd-inline' membuat form tidak mengambil satu baris penuh, sehingga tombol bisa sejajar. --}}
                            <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST" class="d-inline">
                                {{-- @csrf: Token keamanan wajib untuk semua form di Laravel. --}}
                                @csrf
                                {{-- @method('DELETE'): Memberitahu Laravel bahwa form ini sebenarnya adalah permintaan DELETE, bukan POST. --}}
                                @method('DELETE')
                                {{-- onclick="return confirm(...)": JavaScript sederhana untuk memunculkan kotak dialog konfirmasi sebelum form disubmit. --}}
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>

