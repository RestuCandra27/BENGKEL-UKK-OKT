<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                Detail Servis
            </h2>
            <a href="{{ route('montir.servis.index') }}" class="btn btn-secondary btn-sm">
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
        {{-- Kiri: info pelanggan, status & catatan montir --}}
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title mb-0">Info Pelanggan & Unit</h3>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Nama Pelanggan</strong><br>
                        {{ $servis->pelanggan->nama ?? '-' }}<br>
                        <small class="text-muted">{{ $servis->pelanggan->no_hp ?? '-' }}</small>
                    </p>

                    <p>
                        <strong>Kendaraan</strong><br>
                        {{ $servis->kendaraan->merek ?? '-' }}
                        {{ $servis->kendaraan->model ?? '' }}<br>
                        {{ $servis->kendaraan->no_polisi ?? '-' }}
                    </p>

                    <p>
                        <strong>Tanggal Servis</strong><br>
                        {{ \Carbon\Carbon::parse($servis->tanggal_servis)->format('d M Y') }}
                    </p>

                    <p>
                        <strong>Keluhan</strong><br>
                        {{ $servis->keluhan }}
                    </p>

                    <p>
                        <strong>Status Saat Ini</strong><br>
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
                </div>
            </div>

            {{-- Status & Catatan Montir --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">Status & Catatan Montir</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('montir.servis.update-status', $servis->id) }}"
                          method="POST"
                          onsubmit="return confirm('Update status servis dan simpan catatan?');">
                        @csrf

                        <div class="form-group">
                            <label for="catatan_montir">Catatan Montir</label>
                            <textarea
                                name="catatan_montir"
                                id="catatan_montir"
                                rows="3"
                                class="form-control"
                                placeholder="Tuliskan diagnosa, tindakan, atau saran untuk pelanggan...">{{ old('catatan_montir', $servis->catatan_montir) }}</textarea>
                        </div>

                        @if ($servis->status_servis === 'menunggu')
                            <button type="submit" class="btn btn-info btn-block mt-2">
                                Mulai Dikerjakan & Simpan Catatan
                            </button>
                        @elseif ($servis->status_servis === 'dikerjakan')
                            <button type="submit" class="btn btn-success btn-block mt-2">
                                Tandai Selesai & Simpan Catatan
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary btn-block mt-2" disabled>
                                Status tidak bisa diubah oleh montir
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        {{-- Kanan: Jasa & Sparepart (read-only) --}}
        <div class="col-md-8">
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
                                        Belum ada jasa servis yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title mb-0">Sparepart Digunakan</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($servis->detail_spareparts as $part)
                                <tr>
                                    <td>{{ $part->nama_sparepart }}</td>
                                    <td class="text-center">{{ $part->pivot->jumlah_digunakan }}</td>
                                    <td class="text-right">
                                        Rp {{ number_format($part->pivot->jumlah_digunakan * $part->pivot->harga_saat_digunakan, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">
                                        Belum ada sparepart yang digunakan.
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
