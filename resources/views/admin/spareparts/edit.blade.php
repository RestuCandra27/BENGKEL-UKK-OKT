<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Data Sparepart') }}
            </h2>
            <a href="{{ route('admin.spareparts.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
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

            {{-- Form mengarah ke rute update dengan ID sparepart --}}
            <form action="{{ route('admin.spareparts.update', $sparepart->id) }}" method="POST">
                @csrf
                @method('PATCH') {{-- Method PATCH untuk update data --}}

                {{-- Input Kode SKU --}}
                <!-- <div class="form-group">
                    <label for="kode_sku">Kode SKU / Part Number</label>
                    {{-- Value diisi dengan data lama (jika ada error) atau data dari database --}}
                    <input type="text" name="kode_sku" class="form-control" value="{{ old('kode_sku', $sparepart->kode_sku) }}">
                    <small class="form-text text-muted">Boleh dikosongkan jika tidak ada.</small>
                </div> -->

                {{-- Input Nama Sparepart --}}
                <div class="form-group">
                    <label for="nama_sparepart">Nama Sparepart</label>
                    <input type="text" name="nama_sparepart" class="form-control" value="{{ old('nama_sparepart', $sparepart->nama_sparepart) }}" required>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="merek">Merek / Brand</label>
                        <input type="text" name="merek" class="form-control" value="{{ old('merek') }}" placeholder="Contoh: Shell, Honda">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kategori">Kategori</label>
                        <input type="text" name="kategori" class="form-control" value="{{ old('kategori') }}" placeholder="Contoh: Oli Mesin, Ban, Kampas" required>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.spareparts.index') }}" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>
</x-app-layout>