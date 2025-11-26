<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Pendaftaran Servis') }}
            </h2>
            {{-- Tombol menuju form tambah servis --}}
            <a href="{{ route('admin.servis.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Daftar Servis Baru
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px">No</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Kendaraan</th>
                        <th>Keluhan</th>
                        <th>Montir</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($servis_list as $servis)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            
                            {{-- Format Tanggal (misal: 15 Nov 2025) --}}
                            <td>{{ \Carbon\Carbon::parse($servis->tanggal_servis)->format('d M Y') }}</td>
                            
                            {{-- Info Pelanggan --}}
                            <td>
                                <strong>{{ $servis->pelanggan->nama ?? 'User Terhapus' }}</strong><br>
                                <small class="text-muted">{{ $servis->pelanggan->no_hp ?? '-' }}</small>
                            </td>
                            
                            {{-- Info Kendaraan --}}
                            <td>
                                {{ $servis->kendaraan->no_polisi ?? '-' }}<br>
                                <small class="text-muted">
                                    {{ $servis->kendaraan->merek ?? '' }} {{ $servis->kendaraan->model ?? '' }}
                                </small>
                            </td>

                            {{-- Keluhan (dibatasi 50 karakter agar tabel tidak melebar) --}}
                            <td>{{ Str::limit($servis->keluhan, 50) }}</td>

                            {{-- Info Montir --}}
                            <td>{{ $servis->montir->nama ?? 'Belum Ditentukan' }}</td>

                            {{-- Status dengan Warna-warni --}}
                            <td>
                                @php
                                    $badgeClass = match($servis->status_servis) {
                                        'menunggu'   => 'badge-warning',
                                        'dikerjakan' => 'badge-info',
                                        'selesai'    => 'badge-success',
                                        'dibayar'    => 'badge-primary',
                                        'dibatalkan' => 'badge-danger',
                                        default      => 'badge-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($servis->status_servis) }}</span>
                            </td>

                            {{-- Tombol Aksi --}}
                            <td>
                                <a href="{{ route('admin.servis.edit', $servis->id) }}" class="btn btn-sm btn-info">Edit</a>
                                
                                <form action="{{ route('admin.servis.destroy', $servis->id) }}" method="POST" class="inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data servis ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada pendaftaran servis masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $servis_list->links() }}
        </div>
    </div>
</x-app-layout>
