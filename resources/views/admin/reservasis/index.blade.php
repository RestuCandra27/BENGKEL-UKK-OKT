<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                Daftar Reservasi Servis
            </h2>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mt-3">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Pelanggan</th>
                        <th>Kendaraan</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservasis as $r)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($r->tanggal_booking)->format('d M Y') }}</td>
                            <td>{{ $r->jam_booking }}</td>
                            <td>
                                {{ $r->user->nama ?? '-' }}<br>
                                <small class="text-muted">{{ $r->user->email ?? '' }}</small>
                            </td>
                            <td>
                                {{ $r->kendaraan->no_polisi ?? '-' }}<br>
                                <small class="text-muted">
                                    {{ $r->kendaraan->merek ?? '' }} {{ $r->kendaraan->model ?? '' }}
                                </small>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($r->keluhan, 40) }}</td>
                            <td>
                                @php
                                    $status = $r->status ?: 'pending';
                                    $badgeClass = match($status) {
                                        'pending'    => 'badge-warning',
                                        'disetujui'  => 'badge-info',
                                        'ditolak'    => 'badge-danger',
                                        'dibatalkan' => 'badge-secondary',
                                        default      => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.reservasis.show', $r->id) }}"
                                   class="btn btn-sm btn-info">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                Belum ada reservasi masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $reservasis->links() }}
        </div>
    </div>
</x-app-layout>
