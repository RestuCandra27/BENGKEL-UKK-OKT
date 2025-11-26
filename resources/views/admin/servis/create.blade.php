<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pendaftaran Servis Baru') }}
            </h2>
            <a href="{{ route('admin.servis.index') }}" class="btn btn-secondary">Kembali</a>
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

            <form action="{{ route('admin.servis.store') }}" method="POST">
                @csrf

                <div class="row">
                    {{-- Kolom Kiri: Data Pelanggan & Kendaraan --}}
                    <div class="col-md-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Data Pelanggan & Unit</h3>
                            </div>
                            <div class="card-body">
                                {{-- Pilih Pelanggan --}}
                                <div class="form-group">
                                    <label>Pelanggan</label>
                                    <select name="user_id" class="form-control" required>
                                        <option value="">-- Pilih Pelanggan --</option>
                                        @foreach($pelanggans as $p)
                                        <option value="{{ $p->id }}" {{ old('user_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama }} ({{ $p->no_hp ?? '-' }}) - {{ $p->jenis_member }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Pilih Kendaraan --}}
                                <div class="form-group">
                                    <label>Kendaraan</label>
                                    <select name="kendaraan_id" class="form-control" required>
                                        <option value="">-- Pilih Kendaraan --</option>
                                        @foreach($kendaraans as $k)
                                        <option value="{{ $k->id }}" {{ old('kendaraan_id') == $k->id ? 'selected' : '' }}>
                                            [{{ $k->no_polisi }}] {{ $k->merek }} {{ $k->model }} (Milik: {{ $k->user->nama ?? 'Umum' }})
                                        </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Pastikan Nopol sesuai dengan mobil yang datang.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Data Servis --}}
                    <div class="col-md-6">
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Detail Pengerjaan</h3>
                            </div>
                            <div class="card-body">
                                {{-- Tanggal Servis --}}
                                <div class="form-group">
                                    <label>Tanggal Servis</label>
                                    <input type="date" name="tanggal_servis" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>

                                {{-- Pilih Montir --}}
                                <div class="form-group">
                                    <label>Montir Penanggung Jawab</label>
                                    <select name="montir_id" class="form-control" required>
                                        <option value="">-- Pilih Montir --</option>
                                        @foreach($montirs as $m)
                                        <option value="{{ $m->id }}" {{ old('montir_id') == $m->id ? 'selected' : '' }}>
                                            {{ $m->nama }} ({{ $m->kode_montir ?? 'M-??' }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Keluhan --}}
                                <div class="form-group">
                                    <label>Keluhan / Permintaan Servis</label>
                                    <textarea name="keluhan" class="form-control" rows="3" placeholder="Contoh: Ganti oli, rem bunyi, dan cek AC" required>{{ old('keluhan') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            <i class="fas fa-save"></i> Daftarkan Servis
                        </button>
                        <a href="{{ route('admin.servis.index') }}" class="btn btn-secondary btn-block mt-2">Batal</a>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>