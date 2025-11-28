<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Detail Servis') }}
            </h2>

            <a href="{{ route('pelanggan.servis.index') }}" class="btn btn-secondary btn-sm">
                &laquo; Kembali
            </a>
        </div>
    </x-slot>

    <div class="row mt-3">
        {{-- KOLOM KIRI: Info Servis --}}
        <div class="col-md-4">
            <div class="card card-primary card-outline mb-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">Info Servis</h3>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Tanggal Servis:</strong><br>
                        {{ \Carbon\Carbon::parse($servis->tanggal_servis)->format('d M Y') }}
                    </p>

                    <p>
                        <strong>Status:</strong><br>
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
                    </p>

                    <p>
                        <strong>Montir:</strong><br>
                        {{ $servis->montir->nama ?? 'Belum ditentukan' }}
                    </p>

                    <p>
                        <strong>Kendaraan:</strong><br>
                        {{ $servis->kendaraan->no_polisi ?? '-' }}<br>
                        <small class="text-muted">
                            {{ $servis->kendaraan->merek ?? '' }}
                            {{ $servis->kendaraan->model ?? '' }}
                        </small>
                    </p>

                    <p>
                        <strong>Keluhan:</strong><br>
                        {{ $servis->keluhan }}
                    </p>
                </div>
            </div>

            {{-- Ringkasan Biaya --}}
            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title mb-0">Ringkasan Biaya</h3>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Jasa Servis:</strong><br>
                        Rp {{ number_format($totalJasa, 0, ',', '.') }}
                    </p>
                    <p>
                        <strong>Sparepart:</strong><br>
                        Rp {{ number_format($totalSparepart, 0, ',', '.') }}
                    </p>
                    <hr>
                    <h4 class="mb-0">
                        Total: Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Rincian Jasa & Sparepart --}}
        <div class="col-md-8">
            {{-- JASA SERVIS --}}
            <div class="card card-outline card-success mb-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">Jasa Servis</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Nama Layanan</th>
                                <th class="text-right">Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($servis->detail_layanans as $layanan)
                                <tr>
                                    <td>{{ $layanan->nama_layanan }}</td>
                                    <td class="text-right">
                                        Rp {{ number_format($layanan->biaya_standar, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">
                                        Belum ada layanan yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SPAREPART --}}
            <div class="card card-outline card-warning mb-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">Sparepart Digunakan</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Nama Sparepart</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Harga Satuan</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($servis->detail_spareparts as $part)
                                <tr>
                                    <td>{{ $part->nama_sparepart }}</td>
                                    <td class="text-center">{{ $part->pivot->jumlah_digunakan }}</td>
                                    <td class="text-right">
                                        Rp {{ number_format($part->pivot->harga_saat_digunakan, 0, ',', '.') }}
                                    </td>
                                    <td class="text-right">
                                        Rp {{ number_format($part->pivot->harga_saat_digunakan * $part->pivot->jumlah_digunakan, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        Tidak ada sparepart yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
