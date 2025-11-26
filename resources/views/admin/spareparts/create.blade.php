<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Sparepart Baru') }}
            </h2>
            <a href="{{ route('admin.spareparts.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-body">

            {{-- Menampilkan error validasi --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.spareparts.store') }}" method="POST">
                @csrf

                {{-- Input Kode SKU --}}
                <div class="form-group">
                    <label for="kode_sku">Kode SKU / Part Number</label>
                    <input type="text" name="kode_sku" class="form-control" value="{{ old('kode_sku') }}">
                    <small class="form-text text-muted">Boleh dikosongkan jika tidak ada.</small>
                </div>

                {{-- Input Nama Sparepart --}}
                <div class="form-group">
                    <label for="nama_sparepart">Nama Sparepart</label>
                    <input type="text" name="nama_sparepart" class="form-control" value="{{ old('nama_sparepart') }}" required>
                </div>

                {{-- Baris Merek & Kategori (Menggunakan Row agar sejajar) --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="merek">Merek / Brand</label>
                            <input type="text" name="merek" class="form-control" value="{{ old('merek') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <input type="text" name="kategori" class="form-control" value="{{ old('kategori') }}" required>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.spareparts.index') }}" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>
</x-app-layout>