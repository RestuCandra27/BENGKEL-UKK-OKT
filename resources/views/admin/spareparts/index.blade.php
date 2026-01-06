<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Data Sparepart & Stok') }}
            </h2>
            <a href="{{ route('admin.spareparts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Sparepart
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Kode SKU</th>
                        <th>Nama Sparepart</th>
                        <th>Kategori</th>
                        <th>Merek</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($spareparts as $part)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            {{-- Kode SKU --}}
                            <td>{{ $part->kode_sku ?? '-' }}</td>

                            {{-- Nama --}}
                            <td>{{ $part->nama_sparepart }}</td>

                            {{-- Kategori --}}
                            <td>
                                @if($part->kategori)
                                    <span class="badge bg-info">{{ $part->kategori }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Merek --}}
                            <td>{{ $part->merek ?? '-' }}</td>

                            {{-- Harga Jual --}}
                            <td>
                                Rp {{ number_format($part->harga_jual ?? 0, 0, ',', '.') }}
                            </td>

                            {{-- Stok --}}
                            <td>
                                @if (($part->stok ?? 0) <= 0)
                                    <span class="badge bg-secondary">Habis</span>
                                @elseif ($part->is_low_stock ?? false)
                                    <span class="badge bg-danger">{{ $part->stok_label }}</span>
                                @else
                                    <span class="badge bg-success">{{ $part->stok_label }}</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td>
                                <a href="{{ route('admin.spareparts.edit', $part->id) }}"
                                   class="btn btn-sm btn-info">
                                    Edit
                                </a>

                                <form action="{{ route('admin.spareparts.destroy', $part->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus sparepart ini?')">
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
                            <td colspan="8" class="text-center text-muted">
                                Belum ada data sparepart.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($spareparts->hasPages())
            <div class="card-footer clearfix">
                {{ $spareparts->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
