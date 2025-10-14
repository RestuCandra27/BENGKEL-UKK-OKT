{{-- Menggunakan layout utama 'app.blade.php'. Semua konten di dalam sini akan dimasukkan ke dalam '{{ $slot }}' di file layout. --}}
<x-app-layout>
    {{-- Mendefinisikan konten untuk bagian 'header' di layout utama. Ini akan menjadi judul halaman. --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Pelanggan') }}
        </h2>
    </x-slot>

    {{-- BLOK UNTUK MENAMPILKAN PESAN SUKSES --}}
    {{-- @if (session('success')): Logika Blade untuk mengecek "Apakah ada pesan 'success' yang dikirim dari controller (misalnya setelah berhasil menambah data)?" --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{-- Menampilkan isi pesan 'success' --}}
            {{ session('success') }}
        </div>
    @endif

    {{-- 'card' adalah class dari AdminLTE untuk membuat kotak konten utama. --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Semua Pelanggan</h3>
            {{-- 'card-tools' adalah class AdminLTE untuk menempatkan elemen (seperti tombol) di pojok kanan atas card-header. --}}
            <div class="card-tools">
                {{-- Tombol ini mengarah ke rute yang bernama 'admin.pelanggan.create' untuk menampilkan form tambah pelanggan. --}}
                <a href="{{ route('admin.pelanggan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Pelanggan
                </a>
            </div>
        </div>
        <div class="card-body">
            {{-- Class 'table', 'table-bordered', 'table-hover' adalah dari Bootstrap/AdminLTE untuk membuat tabel terlihat bagus. --}}
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Jenis Member</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach: Perintah Blade untuk melakukan perulangan. Ia akan mengulang untuk setiap item di dalam variabel '$pelanggans' yang dikirim dari PelangganController. --}}
                    @foreach ($pelanggans as $pelanggan)
                    <tr>
                        {{-- '$loop->iteration' adalah variabel khusus Blade yang otomatis menghasilkan nomor urut (1, 2, 3, ...) untuk setiap perulangan. --}}
                        <td>{{ $loop->iteration }}</td>
                        {{-- '$pelanggan->user->nama': Ini adalah keajaiban relasi Eloquent. Kita bisa mengakses data 'nama' dari tabel 'User' yang terhubung dengan data pelanggan ini. --}}
                        <td>{{ $pelanggan->user->nama }}</td>
                        <td>{{ $pelanggan->user->email }}</td>
                        {{-- '$pelanggan->no_hp ?? '-'': Tanda '??' (Null Coalescing Operator) artinya "jika $pelanggan->no_hp ada isinya, tampilkan isinya. Jika tidak ada (NULL), tampilkan tanda strip '-'". --}}
                        <td>{{ $pelanggan->no_hp ?? '-' }}</td>
                        {{-- 'badge' dan 'bg-primary' adalah class dari AdminLTE untuk membuat label 'jenis_member' terlihat lebih menarik. --}}
                        <td><span class="badge bg-primary">{{ $pelanggan->jenis_member }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{-- '{{ $pelanggans->links() }}': Perintah Blade yang sangat kuat. Jika Anda menggunakan paginate() di controller, perintah ini akan secara otomatis membuat link nomor halaman (1, 2, 3, ...) yang sudah di-styling dengan benar. --}}
            {{ $pelanggans->links() }}
        </div>
    </div>
</x-app-layout>

