<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Montir
        </h2>
    </x-slot>

    {{-- Kartu Statistik --}}
    <div class="row">

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $total_servis }}</h3>
                    <p>Total Servis Saya</p>
                </div>
                <div class="icon"><i class="fas fa-tools"></i></div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $servis_menunggu }}</h3>
                    <p>Menunggu</p>
                </div>
                <div class="icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $servis_dikerjakan }}</h3>
                    <p>Sedang Dikerjakan</p>
                </div>
                <div class="icon"><i class="fas fa-wrench"></i></div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $servis_selesai }}</h3>
                    <p>Selesai / Dibayar</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>

    </div>

    {{-- Servis Aktif --}}
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title">Servis Aktif</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Kendaraan</th>
                        <th>Status</th>
                        <th width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servis_aktif as $s)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($s->tanggal_servis)->format('d-m-Y') }}</td>
                            <td>{{ $s->pelanggan->nama ?? '-' }}</td>
                            <td>{{ $s->kendaraan->no_polisi ?? '-' }}</td>
                            <td>{{ ucfirst($s->status_servis) }}</td>
                            <td>
                                <a href="{{ route('montir.servis.show', $s->id) }}"
                                   class="btn btn-sm btn-info">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Tidak ada servis aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Riwayat Servis --}}
    <div class="card card-outline card-success mt-3">
        <div class="card-header">
            <h3 class="card-title">5 Servis Terakhir</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Kendaraan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servis_terakhir as $s)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($s->tanggal_servis)->format('d-m-Y') }}</td>
                            <td>{{ $s->pelanggan->nama ?? '-' }}</td>
                            <td>{{ $s->kendaraan->no_polisi ?? '-' }}</td>
                            <td>{{ ucfirst($s->status_servis) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Belum ada servis selesai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
