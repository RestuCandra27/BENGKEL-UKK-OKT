<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                Tambah Kendaraan
            </h2>

            <a href="{{ route('pelanggan.kendaraans.index') }}" class="btn btn-secondary btn-sm">
                &laquo; Kembali
            </a>
        </div>
    </x-slot>

    <div class="card mt-3">
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pelanggan.kendaraans.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>No Polisi</label>
                    <input type="text" name="no_polisi" class="form-control"
                           value="{{ old('no_polisi') }}" required>
                </div>

                <div class="form-group">
                    <label>Merek</label>
                    <input type="text" name="merek" class="form-control"
                           value="{{ old('merek') }}">
                </div>

                <div class="form-group">
                    <label>Model</label>
                    <input type="text" name="model" class="form-control"
                           value="{{ old('model') }}">
                </div>

                <div class="form-group">
                    <label>Tahun</label>
                    <input type="number" name="tahun" class="form-control"
                           value="{{ old('tahun') }}" placeholder="contoh: 2018">
                </div>

                <div class="form-group">
                    <label>Warna</label>
                    <input type="text" name="warna" class="form-control"
                           value="{{ old('warna') }}">
                </div>

                <div class="form-group">
                    <label>Nomor Rangka</label>
                    <input type="text" name="nomor_rangka" class="form-control"
                           value="{{ old('nomor_rangka') }}" required>
                </div>

                <div class="form-group">
                    <label>Nomor Mesin</label>
                    <input type="text" name="nomor_mesin" class="form-control"
                           value="{{ old('nomor_mesin') }}" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
                <a href="{{ route('pelanggan.kendaraans.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </form>
        </div>
    </div>
</x-app-layout>
