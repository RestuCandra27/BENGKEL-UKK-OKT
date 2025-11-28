<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Servis Saya') }}
        </h2>
    </x-slot>

    <div class="card mt-3">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kendaraan</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th>Total Biaya</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($servis_list as $servis)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($servis->tanggal_servis)->format('d M Y') }}</td>

                            <td>
                                {{ $servis->kendaraan->no_polisi ?? '-' }}<br>
                                <small class="text-muted">
                                    {{ $servis->kendaraan->merek ?? '' }}
                                    {{ $servis->kendaraan->model ?? '' }}
                                </small>
                            </td>

                            <td>{{ \Illuminate\Support\Str::limit($servis->keluhan, 50) }}</td>

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

                            <td>Rp {{ number_format($servis->total_biaya ?? 0, 0, ',', '.') }}</td>

                            <td>
                                <a href="{{ route('pelanggan.servis.show', $servis->id) }}"
                                   class="btn btn-sm btn-info">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Belum ada riwayat servis.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $servis_list->links() }}
        </div>
    </div>
</x-app-layout>
