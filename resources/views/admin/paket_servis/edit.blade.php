<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Edit Paket Servis') }}
            </h2>
            <a href="{{ route('admin.paket-servis.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Arahkan ke rute update --}}
            <form action="{{ route('admin.paket-servis.update', $paket->id) }}" method="POST">
                @csrf 
                @method('PUT')

                <div class="form-group">
                    <label>Kode Paket</label>
                    <input type="text" name="kode_paket" class="form-control" value="{{ old('kode_paket', $paket->kode_paket) }}" required>
                </div>

                <div class="form-group">
                    <label>Nama Paket</label>
                    <input type="text" name="nama_paket" class="form-control" value="{{ old('nama_paket', $paket->nama_paket) }}" required>
                </div>

                <div class="form-group">
                    <label>Harga Paket</label>
                    <input type="number" name="harga_paket" class="form-control" value="{{ old('harga_paket', $paket->harga_paket) }}" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi (opsional)</label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $paket->deskripsi) }}</textarea>
                </div>

                {{-- Bagian Checkbox --}}
                <div class="form-group">
                    <label class="d-block">Pilih Layanan (minimal 1)</label>
                    <div class="row">
                        @foreach($layanans as $lay)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="layanan_ids[]" 
                                           value="{{ $lay->id }}" 
                                           id="lay-{{ $lay->id }}"
                                           {{-- LOGIKA CHECKED YANG LEBIH KUAT --}}
                                           {{ in_array($lay->id, old('layanan_ids', $selectedLayananIDs ?? [])) ? 'checked' : '' }}>
                                    
                                    <label class="form-check-label" for="lay-{{ $lay->id }}">
                                        {{ $lay->nama_layanan }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Perbarui Paket</button>
            </form>
        </div>
    </div>
</x-app-layout>