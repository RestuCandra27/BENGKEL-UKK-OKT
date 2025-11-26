{{-- Menggunakan layout utama 'app.blade.php'. --}}
<x-app-layout>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Layanan') }}
            </h2>
            <a href="{{ route('admin.layanans.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-body">

            {{-- Menampilkan error validasi jika ada --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Formulir untuk mengirim data ke fungsi 'update' --}}
            {{-- Arahkan ke rute 'update', kirim ID layanan, dan gunakan method PATCH --}}
            <form action="{{ route('admin.layanans.update', $layanan->id) }}" method="POST">
                @csrf
                @method('PATCH') {{-- Method 'PATCH' untuk update --}}

                {{-- Input Nama Layanan --}}
                <div class="form-group">
                    <label for="nama_layanan">Nama Layanan</label>
                    {{-- 'old()' akan mengambil data lama jika validasi gagal, jika tidak, ambil data dari database --}}
                    <input type="text" name="nama_layanan" class="form-control" value="{{ old('nama_layanan', $layanan->nama_layanan) }}" required>
                </div>

                {{-- Input Biaya Standar --}}
                <div class="form-group">
                    <label for="biaya_standar">Biaya Standar (Rp)</label>
                    <input type="number" name="biaya_standar" class="form-control" value="{{ old('biaya_standar', $layanan->biaya_standar) }}" required step="1000">
                    <small class="form-text text-muted"></small>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi / Keterangan</label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- Tombol Simpan dan Batal --}}
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.layanans.index') }}" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>
</x-app-layout>