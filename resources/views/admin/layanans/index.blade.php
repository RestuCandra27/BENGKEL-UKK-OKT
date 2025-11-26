{{-- Menggunakan layout utama 'app.blade.php'. --}}
<x-app-layout>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Data Layanan') }}
            </h2>
            {{-- Tombol Tambah (Sekarang sudah benar) --}}
            <a href="{{ route('admin.layanans.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Layanan
            </a>
        </div>
    </x-slot>

    {{-- Blok untuk Pesan Sukses/Error --}}
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    {{-- Konten Card --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nama Layanan</th>
                        <th>Biaya Standar (Rp)</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($layanans as $layanan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $layanan->nama_layanan }}</td>
                        <td>{{ number_format($layanan->biaya_standar, 0, ',', '.') }}</td>
                        <td>{{ $layanan->deskripsi ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.layanans.edit', $layanan->id) }}" class="btn btn-sm btn-info">Edit</a>

                            <form action="{{ route('admin.layanans.destroy', $layanan->id) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        {{-- 5 kolom: #, Nama Layanan, Biaya, Deskripsi, Aksi --}}
                        <td colspan="5" class="text-center">Belum ada data layanan.</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</x-app-layout>