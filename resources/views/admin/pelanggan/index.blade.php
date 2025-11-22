{{-- Menggunakan layout utama 'app.blade.php'. --}}
<x-app-layout>
    <x-slot name="header">
        {{-- PERBAIKAN: Memindahkan tombol "Tambah" ke header, sama seperti di Manajemen User --}}
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Data Pelanggan') }}
            </h2>
            <a href="{{ route('admin.pelanggan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pelanggan
            </a>
        </div>
    </x-slot>

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

    <div class="card">
        {{-- PERBAIKAN: Menghapus card-header karena tombol sudah pindah ke atas --}}
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Jenis Member</th>
                        <th>Aksi</th> {{-- PERBAIKAN: Menambahkan kolom Aksi --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pelanggans as $pelanggan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        
                        {{-- PERBAIKAN: Hapus ->user. Ambil data langsung dari $pelanggan --}}
                        <td>{{ $pelanggan->nama }}</td>
                        <td>{{ $pelanggan->email }}</td>
                        
                        {{-- Kode ini sudah benar --}}
                        <td>{{ $pelanggan->no_hp ?? '-' }}</td>
                        <td><span class="badge bg-primary">{{ $pelanggan->jenis_member }}</span></td>

                        {{-- PERBAIKAN: Menambahkan tombol Aksi (Edit & Hapus) --}}
                        <td>
                            <a href="{{ route('admin.pelanggan.edit', $pelanggan->id) }}" class="btn btn-sm btn-info">Edit</a>
                            
                            <form action="{{ route('admin.pelanggan.destroy', $pelanggan->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{-- Link paginasi ini sudah benar --}}
            {{ $pelanggans->links() }}
        </div>
    </div>
</x-app-layout>