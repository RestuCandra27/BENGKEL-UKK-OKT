<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                Buat Reservasi Servis
            </h2>

            <a href="{{ route('pelanggan.reservasis.index') }}" class="btn btn-secondary btn-sm">
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

            <form action="{{ route('pelanggan.reservasis.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Kendaraan</label>
                    <select name="kendaraan_id" class="form-control" required>
                        <option value="">-- Pilih Kendaraan --</option>
                        @foreach($kendaraans as $k)
                            <option value="{{ $k->id }}" {{ old('kendaraan_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->no_polisi }} - {{ $k->merek }} {{ $k->model }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mt-2">
                    <label>Tanggal Booking</label>
                    <input type="date" name="tanggal_booking" class="form-control"
                           value="{{ old('tanggal_booking') }}" required>
                </div>

                <div class="form-group mt-2">
                    <label>Jam Booking</label>
                    <input type="time" name="jam_booking" class="form-control"
                           value="{{ old('jam_booking') }}" required>
                </div>

                <div class="form-group">
    <label for="keluhan">Keluhan / Catatan</label>
    <textarea name="keluhan" id="keluhan" rows="3"
              class="form-control" required>{{ old('keluhan') }}</textarea>
</div>

                <button type="submit" class="btn btn-primary mt-3">
                    Simpan Reservasi
                </button>
            </form>

        </div>
    </div>
</x-app-layout>
