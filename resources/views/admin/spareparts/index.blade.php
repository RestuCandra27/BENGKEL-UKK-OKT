<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Data Sparepart') }}
            </h2>
            <a href="{{ route('admin.spareparts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Sparepart
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
                        <th style="width: 10px">#</th>
                        <th>Kode SKU</th>
                        <th>Nama Sparepart</th>
                        <th>Kategori</th>
                        <th>Merek</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($spareparts as $part)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $part->kode_sku ?? '-' }}</td>
                        <td>{{ $part->nama_sparepart }}</td>
                        <td><span class="badge bg-info">{{ $part->kategori }}</span></td>
                        <td>{{ $part->merek ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.spareparts.edit', $part->id) }}" class="btn btn-sm btn-info">Edit</a>

                            <form action="{{ route('admin.spareparts.destroy', $part->id) }}" method="POST" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus sparepart ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data sparepart.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $spareparts->links() }}
        </div>
    </div>
</x-app-layout>