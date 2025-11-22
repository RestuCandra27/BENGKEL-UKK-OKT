<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Stok Masuk (Pembelian)') }}
            </h2>
            <a href="{{ route('admin.pembelian-spareparts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Stok
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
                        <th>Tanggal</th>
                        <th>Nama Sparepart</th>
                        <th>Jumlah</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok Tersisa (Batch Ini)</th>
                        <th>Aksi</th> {{-- PERBAIKAN: Kolom Aksi --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembelians as $beli)
                    <tr>
                        {{-- Format tanggal Indonesia --}}
                        <td>{{ \Carbon\Carbon::parse($beli->tanggal_masuk)->format('d M Y') }}</td>
                        
                        {{-- Nama sparepart diambil dari relasi --}}
                        <td>{{ $beli->sparepart->nama_sparepart ?? 'Sparepart Terhapus' }}</td>
                        
                        <td>{{ $beli->jumlah_masuk }} pcs</td>
                        <td>Rp {{ number_format($beli->harga_beli, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($beli->harga_jual, 0, ',', '.') }}</td>
                        
                        {{-- Badge stok tersisa --}}
                        <td>
                            @if($beli->stok_tersisa > 0)
                                <span class="badge bg-success">{{ $beli->stok_tersisa }}</span>
                            @else
                                <span class="badge bg-secondary">Habis</span>
                            @endif
                        </td>

                        {{-- PERBAIKAN: Tombol Aksi --}}
                        <td>
                            <a href="{{ route('admin.pembelian-spareparts.edit', $beli->id) }}" class="btn btn-sm btn-info">Edit</a>
                            
                            <form action="{{ route('admin.pembelian-spareparts.destroy', $beli->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data stok ini? Stok akan hilang.')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada riwayat pembelian stok.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $pembelians->links() }}
        </div>
    </div>
</x-app-layout>