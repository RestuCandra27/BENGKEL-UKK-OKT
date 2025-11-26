<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Stok Masuk') }}
            </h2>
            <a href="{{ route('admin.pembelian-spareparts.index') }}" class="btn btn-secondary">Kembali</a>
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

            <form action="{{ route('admin.pembelian-spareparts.update', $pembelian->id) }}" method="POST">
                @csrf
                @method('PATCH')

                {{-- Pilih Sparepart --}}
                <div class="form-group">
                    <label>Nama Sparepart</label>
                    <select name="sparepart_id" class="form-control" required>
                        @foreach($spareparts as $part)
                        <option value="{{ $part->id }}"
                            {{ old('sparepart_id', $pembelian->sparepart_id) == $part->id ? 'selected' : '' }}>
                            {{ $part->nama_sparepart }} (SKU: {{ $part->kode_sku ?? '-' }})
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal Masuk --}}
                <div class="form-group">
                    <label>Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-control"
                        value="{{ old('tanggal_masuk', $pembelian->tanggal_masuk) }}" required>
                </div>

                {{-- Jumlah Masuk --}}
                <div class="form-group">
                    <label>Jumlah Masuk (Pcs)</label>
                    <input type="number" name="jumlah_masuk" class="form-control" min="1"
                        value="{{ old('jumlah_masuk', $pembelian->jumlah_masuk) }}" required>
                    <small class="text-warning">Perhatian: Mengubah jumlah akan me-reset stok tersisa menjadi jumlah baru.</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Harga Beli Satuan (Rp)</label>
                            <input type="number" name="harga_beli" class="form-control" min="0"
                                value="{{ old('harga_beli', $pembelian->harga_beli) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Harga Jual Satuan (Rp)</label>
                            <input type="number" name="harga_jual" class="form-control" min="0"
                                value="{{ old('harga_jual', $pembelian->harga_jual) }}" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Stok</button>
                <a href="{{ route('admin.pembelian-spareparts.index') }}" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>
</x-app-layout>