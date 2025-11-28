<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
            Daftar Servis Saya
        </h2>
    </x-slot>

    <div class="card mt-3">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Kendaraan</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th style="width: 100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($servis_list as $servis)
                        <tr>
                            <td>{{ $loop->iteration + ($servis_list->currentPage() - 1) * $servis_list->perPage() }}</td>
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
                            <td colspan="7" class="text-center">
                                Belum ada servis yang ditugaskan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $servis_list->links() }}
        </div>
    </div>
</x-app-layout>
