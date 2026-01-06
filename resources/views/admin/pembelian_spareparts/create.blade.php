<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Input Stok Masuk') }}
            </h2>
            <a href="{{ route('admin.stok-masuk.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="card mt-3">
        <div class="card-body">

            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.stok-masuk.store') }}" method="POST">
                @csrf

                {{-- Pilih Sparepart --}}
                <div class="form-group">
                    <label>Nama Sparepart</label>
                    <select name="sparepart_id" class="form-control" required>
                        <option value="">-- Pilih Sparepart --</option>
                        @foreach($spareparts as $part)
                        <option value="{{ $part->id }}">
                            {{ $part->nama_sparepart }} (SKU: {{ $part->kode_sku ?? '-' }})
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal Masuk --}}
                <div class="form-group">
                    <label>Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-control"
                        value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                </div>

                {{-- Jumlah Masuk --}}
                <div class="form-group">
                    <label>Jumlah Masuk (Pcs)</label>
                    <input type="number" name="jumlah_masuk" class="form-control"
                        min="1" value="{{ old('jumlah_masuk') }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        {{-- Harga Beli --}}
                        <div class="form-group">
                            <label>Harga Beli Satuan (Rp)</label>
                            <input type="number" name="harga_beli" class="form-control"
                                min="0" value="{{ old('harga_beli') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- 🔹 Harga Jual Satuan --}}
                        <div class="form-group">
                            <label>Harga Jual Satuan (Rp)</label>
                            <input type="number" name="harga_jual" class="form-control"
                                min="0" value="{{ old('harga_jual') }}" required>
                            <small class="text-muted">Harga jual ke pelanggan.</small>
                        </div>
                    </div>
                </div>

                {{-- Keterangan (opsional) --}}
                <div class="form-group">
                    <label>Keterangan (Opsional)</label>
                    <textarea name="keterangan" class="form-control"
                        placeholder="Contoh: Pembelian dari Supplier A, koreksi stok, dll.">{{ old('keterangan') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Stok
                </button>
                <a href="{{ route('admin.stok-masuk.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </form>

        </div>
    </div>
</x-app-layout>