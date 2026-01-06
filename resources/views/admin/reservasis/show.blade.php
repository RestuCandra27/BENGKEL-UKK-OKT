<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                Detail Reservasi (Admin)
            </h2>

            <a href="{{ route('admin.reservasis.index') }}" class="btn btn-secondary btn-sm">
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
        {{-- INFO RESERVASI --}}
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title mb-0">Info Reservasi</h3>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Pelanggan:</strong><br>
                        {{ $reservasi->user->nama ?? '-' }}<br>
                        <small class="text-muted">{{ $reservasi->user->email ?? '' }}</small>
                    </p>

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
                        $status = $reservasi->status ?: 'pending';
                        $badgeClass = match($status) {
                        'pending' => 'badge-warning',
                        'disetujui' => 'badge-info',
                        'ditolak' => 'badge-danger',
                        'dibatalkan' => 'badge-secondary',
                        default => 'badge-secondary',
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

            {{-- TOMBOL SETUJUI / TOLAK (hanya kalau belum ditolak / dibatalkan) --}}
            @if (in_array($reservasi->status, ['pending', 'disetujui', null]))
            <div class="card mt-3">
                <div class="card-body">
                    @if ($reservasi->status !== 'disetujui')
                    <form action="{{ route('admin.reservasis.approve', $reservasi->id) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Pilih Montir</label>
                            <select name="montir_id" class="form-control" required>
                                <option value="">-- Pilih Montir --</option>
                                @foreach($montirs as $montir)
                                <option value="{{ $montir->id }}">{{ $montir->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">
                            Setujui & Buat Servis
                        </button>
                    </form>

                    @endif

                    @if ($reservasi->status !== 'ditolak')
                    <form action="{{ route('admin.reservasis.reject', $reservasi->id) }}"
                        method="POST"
                        class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Tolak reservasi ini?');">
                            Tolak
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- INFO KENDARAAN --}}
        <div class="col-md-6">
            <div class="card card-info card-outline">
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