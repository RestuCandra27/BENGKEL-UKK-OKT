{{-- Menggunakan layout utama 'app.blade.php'. Semua konten di dalam sini akan dimasukkan ke dalam '{{ $slot }}' di file layout. --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight m-0">
                {{ __('Tambah Pelanggan Baru') }}
            </h2>

            <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary">Kembali</a>
            </a>
        </div>
    </x-slot>


    {{-- 'card' dan 'card-primary' adalah class dari AdminLTE untuk membuat kotak konten utama dengan aksen warna biru. --}}
    <div class="card card-primary mt-3">
        <div class="card-header bg-primary text-white">
            {{-- WRAPPER FLEX DI DALAM CARD-HEADER --}}
            <div class="d-flex justify-content-between align-items-center w-100">
                <h3 class="card-title mb-0">Form Tambah Pelanggan</h3>

                {{-- Tombol kembali di sebelah kanan judul form --}}

            </div>
        </div>

        {{-- FORMULIR UTAMA --}}
        <form action="{{ route('admin.pelanggan.store') }}" method="POST">
            @csrf

            <div class="card-body">

                {{-- BLOK UNTUK MENAMPILKAN ERROR VALIDASI --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    <h6>Terdapat Kesalahan:</h6>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <p class="text-muted">Bagian Akun Login</p>
                <hr>

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" id="nama"
                        value="{{ old('nama') }}" placeholder="Masukkan Nama Lengkap" required>
                </div>

                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" name="email" class="form-control" id="email"
                        value="{{ old('email') }}" placeholder="Masukkan Email" required>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Password" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                id="password_confirmation" placeholder="Konfirmasi Password" required>
                        </div>
                    </div>
                </div>

                <br>
                <p class="text-muted">Bagian Profil Pelanggan</p>
                <hr>

                <div class="form-group">
                    <label for="no_hp">Nomor HP</label>
                    <input type="text" name="no_hp" class="form-control" id="no_hp"
                        value="{{ old('no_hp') }}" placeholder="Masukkan Nomor HP">
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" class="form-control" id="alamat" rows="3"
                        placeholder="Masukkan Alamat">{{ old('alamat') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="jenis_member">Jenis Member</label>
                    <select name="jenis_member" class="form-control" id="jenis_member" required>
                        <option value="Reguler" {{ old('jenis_member') == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="VIP" {{ old('jenis_member') == 'VIP' ? 'selected' : '' }}>VIP</option>
                        <option value="Fleet" {{ old('jenis_member') == 'Fleet' ? 'selected' : '' }}>Fleet</option>
                    </select>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>