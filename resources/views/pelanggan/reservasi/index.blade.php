<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Booking / Reservasi Servis') }}
            </h2>

            <a href="{{ route('pelanggan.reservasis.create') }}" class="btn btn-primary">
                + Buat Reservasi
            </a>
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
                        <th>Kendaraan</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasis as $r)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($r->tanggal_booking)->format('d M Y') }}</td>
                            <td>{{ $r->jam_booking }}</td>
                            <td>
                                {{ $r->kendaraan->no_polisi ?? '-' }}<br>
                                <small class="text-muted">
                                    {{ $r->kendaraan->merek ?? '' }} {{ $r->kendaraan->model ?? '' }}
                                </small>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($r->keluhan, 40) }}</td>
                            <td>
                                @php
                                    // kalau status masih null, anggap pending
                                    $status = $r->status ?: 'pending';

                                    $badgeClass = match ($status) {
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
                                {{-- Tombol Detail --}}
                                <a href="{{ route('pelanggan.reservasis.show', $r->id) }}"
                                   class="btn btn-sm btn-info">
                                    Detail
                                </a>

                                {{-- Tombol Batalkan hanya kalau pending / disetujui --}}
                                @php
                                    $bisaBatal = in_array($r->status, ['pending', 'disetujui', null]);
                                @endphp

                                @if ($bisaBatal)
                                    <form action="{{ route('pelanggan.reservasis.cancel', $r->id) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Batalkan reservasi ini?')">
                                            Batalkan
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Belum ada reservasi yang Anda buat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $reservasis->links() }}
        </div>
    </div>
</x-app-layout>
