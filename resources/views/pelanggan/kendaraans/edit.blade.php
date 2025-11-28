<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                Edit Kendaraan: {{ $kendaraan->no_polisi }}
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

            <form action="{{ route('pelanggan.kendaraans.update', $kendaraan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>No Polisi</label>
                    <input type="text" name="no_polisi" class="form-control"
                           value="{{ old('no_polisi', $kendaraan->no_polisi) }}" required>
                </div>

                <div class="form-group">
                    <label>Merek</label>
                    <input type="text" name="merek" class="form-control"
                           value="{{ old('merek', $kendaraan->merek) }}">
                </div>

                <div class="form-group">
                    <label>Model</label>
                    <input type="text" name="model" class="form-control"
                           value="{{ old('model', $kendaraan->model) }}">
                </div>

                <div class="form-group">
                    <label>Tahun</label>
                    <input type="number" name="tahun" class="form-control"
                           value="{{ old('tahun', $kendaraan->tahun) }}"
                           placeholder="contoh: 2018">
                </div>

                <div class="form-group">
                    <label>Warna</label>
                    <input type="text" name="warna" class="form-control"
                           value="{{ old('warna', $kendaraan->warna) }}">
                </div>

                <div class="form-group">
                    <label>Nomor Rangka</label>
                    <input type="text" name="nomor_rangka" class="form-control"
                           value="{{ old('nomor_rangka', $kendaraan->nomor_rangka) }}" required>
                </div>

                <div class="form-group">
                    <label>Nomor Mesin</label>
                    <input type="text" name="nomor_mesin" class="form-control"
                           value="{{ old('nomor_mesin', $kendaraan->nomor_mesin) }}" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Update
                </button>
                <a href="{{ route('pelanggan.kendaraans.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </form>
        </div>
    </div>
</x-app-layout>
