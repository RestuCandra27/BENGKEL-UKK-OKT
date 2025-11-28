<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
            Dashboard Montir
        </h2>
    </x-slot>

    <div class="row mt-3">
        <div class="col-md-4">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $total_pending }}</h3>
                    <p>Servis Menunggu</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="{{ route('montir.servis.index') }}" class="small-box-footer">
                    Lihat Servis <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $total_progress }}</h3>
                    <p>Servis Sedang Dikerjakan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
                <a href="{{ route('montir.servis.index') }}" class="small-box-footer">
                    Lihat Servis <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $total_selesai }}</h3>
                    <p>Servis Selesai</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="{{ route('montir.servis.index') }}" class="small-box-footer">
                    Lihat Servis <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Opsional: tabel servis terbaru --}}
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title mb-0">Servis Terbaru Anda</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Kendaraan</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th style="width: 90px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(
                        \App\Models\Servis::with(['pelanggan', 'kendaraan'])
                            ->where('montir_id', \Illuminate\Support\Facades\Auth::id())
                            ->latest()
                            ->take(5)
                            ->get() as $servis
                    )
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($servis->tanggal_servis)->format('d M Y') }}</td>
                            <td>{{ $servis->pelanggan->nama ?? '-' }}</td>
                            <td>{{ $servis->kendaraan->no_polisi ?? '-' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($servis->keluhan, 40) }}</td>
                            <td>
                                @php
                                    $badgeClass = match($servis->status_servis) {
                                        'menunggu'   => 'badge-warning',
                                        'dikerjakan' => 'badge-info',
                                        'selesai'    => 'badge-success',
                                        'dibayar'    => 'badge-primary',
                                        'dibatalkan' => 'badge-danger',
                                        default      => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($servis->status_servis) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('montir.servis.show', $servis->id) }}"
                                   class="btn btn-sm btn-info">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Belum ada tugas servis untuk Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
