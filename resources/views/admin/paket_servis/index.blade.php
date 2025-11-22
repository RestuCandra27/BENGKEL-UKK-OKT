<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Data Paket Servis') }}
            </h2>
            <a href="{{ route('admin.paket-servis.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Paket Baru
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
                        <th>Kode Paket</th>
                        <th>Nama Paket</th>
                        <th>Isi Paket (Layanan)</th>
                        <th>Harga Paket</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pakets as $paket)
                    <tr>
                        <td>{{ $paket->kode_paket }}</td>
                        <td>
                            <strong>{{ $paket->nama_paket }}</strong><br>
                            <small class="text-muted">{{ $paket->deskripsi }}</small>
                        </td>
                        
                        {{-- Menampilkan daftar layanan dalam paket --}}
                        <td>
                            <ul class="pl-3 mb-0">
                                @foreach($paket->layanans as $layanan)
                                    <li>{{ $layanan->nama_layanan }}</li>
                                @endforeach
                            </ul>
                        </td>
                        
                        <td>Rp {{ number_format($paket->harga_paket, 0, ',', '.') }}</td>
                        
                        <td>
                            <a href="{{ route('admin.paket-servis.edit', $paket->id) }}" class="btn btn-sm btn-info">Edit</a>
                            
                            <form action="{{ route('admin.paket-servis.destroy', $paket->id) }}" method="POST" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus paket ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada paket servis.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $pakets->links() }}
        </div>
    </div>
</x-app-layout>