<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Stok Masuk') }}
            </h2>
            <a href="{{ route('admin.stok-masuk.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Stok
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success mt-2">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mt-2">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Sparepart</th>
                        <th>Jumlah Masuk</th>
                        <th>Harga Beli / Unit</th>
                        <th>Harga Jual / Unit</th>     {{-- 🔹 kolom baru --}}
                        <th>Subtotal Beli</th>
                        <th>Subtotal Jual</th>        {{-- 🔹 kolom baru --}}
                        <th>Keterangan</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembelians as $beli)
                        <tr>
                            {{-- Tanggal --}}
                            <td>
                                {{ \Carbon\Carbon::parse($beli->tanggal_masuk)->format('d M Y') }}
                            </td>

                            {{-- Nama sparepart --}}
                            <td>
                                {{ $beli->sparepart->nama_sparepart ?? 'Sparepart terhapus' }}
                            </td>

                            {{-- Jumlah masuk --}}
                            <td>{{ $beli->jumlah_masuk }} pcs</td>

                            {{-- Harga beli per unit --}}
                            <td>
                                @if (!is_null($beli->harga_beli))
                                    Rp {{ number_format($beli->harga_beli, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- 🔹 Harga jual per unit --}}
                            <td>
                                @if (!is_null($beli->harga_jual))
                                    Rp {{ number_format($beli->harga_jual, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Subtotal beli (modal) --}}
                            <td>
                                @if (!is_null($beli->harga_beli))
                                    Rp {{ number_format($beli->harga_beli * $beli->jumlah_masuk, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- 🔹 Subtotal jual (teoritis) --}}
                            <td>
                                @if (!is_null($beli->harga_jual))
                                    Rp {{ number_format($beli->harga_jual * $beli->jumlah_masuk, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Keterangan --}}
                            <td>
                                {{ $beli->keterangan ?? '-' }}
                            </td>

                            {{-- Aksi --}}
                            <td>
                                <form action="{{ route('admin.stok-masuk.destroy', $beli) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus data stok masuk ini? Stok sparepart akan dikoreksi jika masih mencukupi.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                Belum ada data stok masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pembelians->hasPages())
            <div class="card-footer clearfix">
                {{ $pembelians->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
