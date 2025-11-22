<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Stok Masuk') }}
        </h2>
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
            
            <form action="{{ route('admin.pembelian-spareparts.store') }}" method="POST">
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
                    {{-- value default hari ini --}}
                    <input type="date" name="tanggal_masuk" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                {{-- Jumlah Masuk --}}
                <div class="form-group">
                    <label>Jumlah Masuk (Pcs)</label>
                    <input type="number" name="jumlah_masuk" class="form-control" min="1" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        {{-- Harga Beli --}}
                        <div class="form-group">
                            <label>Harga Beli Satuan (Rp)</label>
                            <input type="number" name="harga_beli" class="form-control" min="0" required>
                            <small class="text-muted">Harga modal per barang.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- Harga Jual --}}
                        <div class="form-group">
                            <label>Harga Jual Satuan (Rp)</label>
                            <input type="number" name="harga_jual" class="form-control" min="0" required>
                            <small class="text-muted">Harga untuk pelanggan (Batch ini).</small>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Stok</button>
                <a href="{{ route('admin.pembelian-spareparts.index') }}" class="btn btn-secondary">Batal</a>
            </form>
            
        </div>
    </div>
</x-app-layout>