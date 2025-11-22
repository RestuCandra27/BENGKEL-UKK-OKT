<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kendaraan Baru') }}
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
            
            <form action="{{ route('admin.kendaraans.store') }}" method="POST">
                @csrf

                {{-- PILIH PEMILIK (Dropdown dari data User) --}}
                <div class="form-group">
                    <label>Pemilik Kendaraan (Pelanggan)</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($pelanggans as $p)
                            <option value="{{ $p->id }}" {{ old('user_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }} ({{ $p->no_hp ?? 'No HP -' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- No Polisi --}}
                <div class="form-group">
                    <label>Nomor Polisi (Plat Nomor)</label>
                    <input type="text" name="no_polisi" class="form-control" value="{{ old('no_polisi') }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Merek</label>
                            <input type="text" name="merek" class="form-control" value="{{ old('merek') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Model / Tipe</label>
                            <input type="text" name="model" class="form-control" value="{{ old('model') }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tahun Pembuatan</label>
                            <input type="number" name="tahun" class="form-control" value="{{ old('tahun') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Warna</label>
                            <input type="text" name="warna" class="form-control" value="{{ old('warna') }}">
                        </div>
                    </div>
                </div>

                {{-- Data Teknis (Opsional tapi penting untuk bengkel) --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nomor Rangka (VIN)</label>
                            <input type="text" name="nomor_rangka" class="form-control" value="{{ old('nomor_rangka') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nomor Mesin</label>
                            <input type="text" name="nomor_mesin" class="form-control" value="{{ old('nomor_mesin') }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Kendaraan</button>
                <a href="{{ route('admin.kendaraans.index') }}" class="btn btn-secondary">Batal</a>
            </form>
            
        </div>
    </div>
</x-app-layout>