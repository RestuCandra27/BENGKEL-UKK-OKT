{{-- Menggunakan layout utama 'app.blade.php'. Semua konten di dalam sini akan dimasukkan ke dalam '{{ $slot }}' di file layout. --}}
<x-app-layout>
    {{-- Mendefinisikan konten untuk bagian 'header' di layout utama. Ini akan menjadi judul halaman. --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pelanggan Baru') }}
        </h2>
    </x-slot>

    {{-- 'card' dan 'card-primary' adalah class dari AdminLTE untuk membuat kotak konten utama dengan aksen warna biru. --}}
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Tambah Pelanggan</h3>
        </div>
        
        {{-- FORMULIR UTAMA --}}
        {{-- action="{{ route('admin.pelanggan.store') }}": Saat disubmit, form ini akan mengirim data ke rute yang bernama 'admin.pelanggan.store'. --}}
        <form action="{{ route('admin.pelanggan.store') }}" method="POST">
            {{-- @csrf: Perintah Blade yang WAJIB ada di setiap form. Ini membuat "tiket keamanan" rahasia untuk melindungi dari serangan CSRF. --}}
            @csrf
            
            <div class="card-body">

                {{-- BLOK UNTUK MENAMPILKAN ERROR VALIDASI --}}
                {{-- @if ($errors->any()): Logika Blade untuk mengecek "Apakah ada error validasi yang dikirim dari controller?" --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h6>Terdapat Kesalahan:</h6>
                        <ul>
                            {{-- @foreach: Jika ada error, lakukan perulangan dan tampilkan setiap pesan error dalam bentuk list. --}}
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <p class="text-muted">Bagian Akun Login</p>
                <hr>

                {{-- Bagian untuk input Nama Lengkap --}}
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    {{-- value="{{ old('nama') }}": Helper Laravel yang sangat berguna. Jika form gagal divalidasi, input ini akan otomatis terisi kembali dengan data yang sebelumnya diinput oleh user, sehingga user tidak perlu mengetik ulang. --}}
                    <input type="text" name="nama" class="form-control" id="nama" value="{{ old('nama') }}" placeholder="Masukkan Nama Lengkap" required>
                </div>

                {{-- Bagian untuk input Email --}}
                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" placeholder="Masukkan Email" required>
                </div>

                {{-- 'row' dan 'col-sm-6' adalah class Bootstrap/AdminLTE untuk membuat layout dua kolom. --}}
                <div class="row">
                    <div class="col-sm-6">
                        {{-- Bagian untuk input Password --}}
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        {{-- Bagian untuk Konfirmasi Password. Laravel akan otomatis mencocokkan isinya dengan input 'password'. --}}
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Konfirmasi Password" required>
                        </div>
                    </div>
                </div>
                <br>
                <p class="text-muted">Bagian Profil Pelanggan</p>
                <hr>

                {{-- Bagian untuk input Nomor HP --}}
                <div class="form-group">
                    <label for="no_hp">Nomor HP</label>
                    <input type="text" name="no_hp" class="form-control" id="no_hp" value="{{ old('no_hp') }}" placeholder="Masukkan Nomor HP">
                </div>

                {{-- Bagian untuk input Alamat --}}
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    {{-- Untuk textarea, helper 'old()' diletakkan di antara tag pembuka dan penutup. --}}
                    <textarea name="alamat" class="form-control" id="alamat" rows="3" placeholder="Masukkan Alamat">{{ old('alamat') }}</textarea>
                </div>

                {{-- Bagian untuk memilih Jenis Member --}}
                <div class="form-group">
                    <label for="jenis_member">Jenis Member</label>
                    <select name="jenis_member" class="form-control" id="jenis_member" required>
                        {{-- Logika Blade `{{ old(...) == '...' ? 'selected' : '' }}` digunakan untuk memilih kembali opsi yang sebelumnya dipilih jika terjadi error validasi. --}}
                        <option value="Reguler" {{ old('jenis_member') == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="VIP" {{ old('jenis_member') == 'VIP' ? 'selected' : '' }}>VIP</option>
                        <option value="Fleet" {{ old('jenis_member') == 'Fleet' ? 'selected' : '' }}>Fleet</option>
                    </select>
                </div>
            </div>
            
            <div class="card-footer">
                {{-- Tombol untuk mengirim (submit) form --}}
                <button type="submit" class="btn btn-primary">Simpan</button>
                {{-- Tombol untuk batal, akan mengarahkan kembali ke halaman daftar pelanggan --}}
                <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>

