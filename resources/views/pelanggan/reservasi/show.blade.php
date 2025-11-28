<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                Detail Reservasi
            </h2>

            <a href="{{ route('pelanggan.reservasis.index') }}" class="btn btn-secondary btn-sm">
                &laquo; Kembali
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

    <div class="row mt-3">
        {{-- KOLOM KIRI: INFO RESERVASI --}}
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title mb-0">Info Reservasi</h3>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Tanggal Booking:</strong><br>
                        {{ \Carbon\Carbon::parse($reservasi->tanggal_booking)->format('d M Y') }}
                    </p>

                    <p>
                        <strong>Jam Booking:</strong><br>
                        {{ $reservasi->jam_booking }}
                    </p>

                    <p>
                        <strong>Status:</strong><br>
                        @php
                            // fallback: kalau status null anggap pending
                            $status = $reservasi->status ?: 'pending';

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
                    </p>

                    <p>
                        <strong>Keluhan:</strong><br>
                        {{ $reservasi->keluhan ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- FORM PEMBATALAN (HANYA JIKA MASIH BOLEH DIBATALKAN) --}}
            @if ($bisaDibatalkan)
                <div class="card mt-3">
                    <div class="card-body">
                        <form action="{{ route('pelanggan.reservasis.cancel', $reservasi->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin membatalkan reservasi ini?');">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                Batalkan Reservasi
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- KOLOM KANAN: INFO KENDARAAN --}}
        <div class="col-md-6">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title mb-0">Info Kendaraan</h3>
                </div>
                <div class="card-body">
                    <p>
                        <strong>No. Polisi:</strong><br>
                        {{ $reservasi->kendaraan->no_polisi ?? '-' }}
                    </p>

                    <p>
                        <strong>Merek / Model:</strong><br>
                        {{ $reservasi->kendaraan->merek ?? '-' }}
                        {{ $reservasi->kendaraan->model ?? '' }}
                    </p>

                    <p>
                        <strong>Tahun:</strong><br>
                        {{ $reservasi->kendaraan->tahun ?? '-' }}
                    </p>

                    <p>
                        <strong>Warna:</strong><br>
                        {{ $reservasi->kendaraan->warna ?? '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
