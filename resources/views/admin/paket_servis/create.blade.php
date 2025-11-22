<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Buat Paket Servis') }}
            </h2>
            {{-- Tombol Kembali Inisiatif Anda (Sudah Benar) --}}
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

            <form action="{{ route('admin.paket-servis.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Kode Paket</label>
                    <input type="text" name="kode_paket" class="form-control" value="{{ old('kode_paket') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Paket</label>
                    <input type="text" name="nama_paket" class="form-control" value="{{ old('nama_paket') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga Paket</label>
                    <input type="number" name="harga_paket" class="form-control" value="{{ old('harga_paket') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi (opsional)</label>
                    <textarea name="deskripsi" class="form-control">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pilih Layanan (minimal 1)</label>
                    <div class="form-check">
                        @foreach($layanans as $lay)
                            <div>
                                <input class="form-check-input" type="checkbox" name="layanan_ids[]" value="{{ $lay->id }}" id="lay-{{ $lay->id }}"
                                    {{-- PERBAIKAN: Agar centangan tidak hilang saat validasi gagal --}}
                                    {{ (is_array(old('layanan_ids')) && in_array($lay->id, old('layanan_ids'))) ? 'checked' : '' }}>
                                
                                <label class="form-check-label" for="lay-{{ $lay->id }}">{{ $lay->nama_layanan }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button class="btn btn-primary">Simpan Paket</button>
            </form>
        </div>
    </div>
</x-app-layout>