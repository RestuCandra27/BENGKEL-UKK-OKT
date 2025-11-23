{{-- Menggunakan layout utama 'app.blade.php'. --}}
<x-app-layout>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Layanan Baru') }}
        </h2>
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

            {{-- Formulir untuk mengirim data ke fungsi 'store' --}}
            <form action="{{ route('admin.layanans.store') }}" method="POST">
                @csrf {{-- Token Keamanan Wajib --}}

                {{-- Input Nama Layanan --}}
                <div class="form-group">
                    <label for="nama_layanan">Nama Layanan</label>
                    <input type="text" name="nama_layanan" class="form-control" value="{{ old('nama_layanan') }}" required>
                </div>

                {{-- Input Biaya Standar --}}
                <div class="form-group">
                    <label for="biaya_standar">Biaya Standar (Rp)</label>
                    <input type="number" name="biaya_standar" class="form-control" value="{{ old('biaya_standar') }}" required step="1000">
                    <small class="form-text text-muted"></small>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi / Keterangan</label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- Tombol Simpan dan Batal --}}
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.layanans.index') }}" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>
</x-app-layout>