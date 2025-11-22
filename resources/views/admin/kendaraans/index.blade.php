<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Data Kendaraan') }}
            </h2>
            <a href="{{ route('admin.kendaraans.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Kendaraan
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No. Polisi</th>
                        <th>Pemilik</th>
                        <th>Merek & Model</th>
                        <th>Warna/Tahun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kendaraans as $k)
                    <tr>
                        <td class="font-weight-bold">{{ $k->no_polisi }}</td>
                        
                        {{-- Menampilkan nama pemilik dari relasi --}}
                        <td>{{ $k->user->nama ?? 'Tanpa Pemilik' }}</td>
                        
                        <td>{{ $k->merek }} - {{ $k->model }}</td>
                        <td>{{ $k->warna }} ({{ $k->tahun }})</td>
                        
                        <td>
                            <a href="{{ route('admin.kendaraans.edit', $k->id) }}" class="btn btn-sm btn-info">Edit</a>
                            
                            <form action="{{ route('admin.kendaraans.destroy', $k->id) }}" method="POST" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kendaraan ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data kendaraan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $kendaraans->links() }}
        </div>
    </div>
</x-app-layout>