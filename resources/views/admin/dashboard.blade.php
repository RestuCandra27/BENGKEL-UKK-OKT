<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1" style="color:#e5e7eb;">
                    Dashboard Bengkel
                </h2>
                <p class="mb-0" style="font-size:0.875rem; color:#9ca3af;">
                    Ringkasan aktivitas & performa Candra Garage.
                </p>
            </div>

            <span class="badge badge-pill"
                  style="background:linear-gradient(135deg,#22d3ee,#a855f7);color:#0f172a;padding:.45rem .9rem;">
                {{ now()->translatedFormat('d F Y') }}
            </span>
        </div>
    </x-slot>

    {{-- ROW 1: Kartu utama --}}
    <div class="row">
        {{-- Total Pelanggan --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <span style="font-size:.75rem; text-transform:uppercase; letter-spacing:.16em; color:#e5e7eb;">
                        Pelanggan
                    </span>
                    <h3 class="mt-1">{{ $total_pelanggan }}</h3>
                    <p class="mb-0">Total Pelanggan Terdaftar</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.pelanggan.index') }}" class="small-box-footer">
                    Lihat data pelanggan <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Total Montir --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <span style="font-size:.75rem; text-transform:uppercase; letter-spacing:.16em; color:#e5e7eb;">
                        Montir
                    </span>
                    <h3 class="mt-1">{{ $total_montir }}</h3>
                    <p class="mb-0">Total Montir Aktif</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hard-hat"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                    Lihat data montir <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Servis Hari Ini --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <span style="font-size:.75rem; text-transform:uppercase; letter-spacing:.16em; color:#e5e7eb;">
                        Servis Hari Ini
                    </span>
                    <h3 class="mt-1">{{ $servis_hari_ini }}</h3>
                    <p class="mb-0">Unit terjadwal hari ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
                <a href="{{ route('admin.servis.index') }}" class="small-box-footer">
                    Lihat daftar servis <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Invoice Belum Lunas --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <span style="font-size:.75rem; text-transform:uppercase; letter-spacing:.16em; color:#e5e7eb;">
                        Invoice
                    </span>
                    <h3 class="mt-1">{{ $invoice_belum_lunas }}</h3>
                    <p class="mb-0">Invoice belum lunas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <a href="{{ route('admin.invoices.index') }}" class="small-box-footer">
                    Lihat semua invoice <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- ROW 2: Statistik bulan ini --}}
    <div class="row">
        {{-- Omzet Bulan Ini --}}
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Omzet Bulan Ini</h3>
                </div>
                <div class="card-body">
                    <h2 class="mb-1">
                        Rp {{ number_format($omzet_bulan_ini, 0, ',', '.') }}
                    </h2>
                    <small style="color:#9ca3af;">
                        Total invoice <strong>lunas</strong> bulan {{ now()->translatedFormat('F Y') }}.
                    </small>
                </div>
            </div>
        </div>

        {{-- Servis Selesai Bulan Ini --}}
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Servis Selesai Bulan Ini</h3>
                </div>
                <div class="card-body">
                    <h2 class="mb-1">{{ $servis_selesai_bulan }}</h2>
                    <small style="color:#9ca3af;">
                        Jumlah servis dengan status <strong>selesai</strong> di bulan ini.
                    </small>
                </div>
            </div>
        </div>

        {{-- Ringkasan Cepat --}}
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Ringkasan Cepat</h3>
                </div>
                <div class="card-body">
                    <p class="mb-1">
                        • <strong>{{ $servis_hari_ini }}</strong> servis terjadwal hari ini.
                    </p>
                    <p class="mb-1">
                        • <strong>{{ $invoice_belum_lunas }}</strong> invoice masih menunggu pelunasan.
                    </p>
                    <p class="mb-0">
                        • Omzet bulan ini:
                        <strong>Rp {{ number_format($omzet_bulan_ini, 0, ',', '.') }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 3: Tabel ringkasan --}}
    <div class="row">
        {{-- Servis terbaru --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Servis Terbaru</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.servis.index') }}" class="btn btn-tool">
                            Lihat semua <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
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
                            @forelse ($servis_terbaru as $s)
                                <tr>
                                    <td>{{ optional($s->tanggal_servis)->format('d-m-Y') }}</td>
                                    <td>{{ $s->pelanggan->nama ?? '-' }}</td>
                                    <td>{{ $s->kendaraan->no_polisi ?? '-' }}</td>
                                    <td>{{ ucfirst($s->status_servis) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center" style="color:#9ca3af;">
                                        Belum ada data servis.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Invoice terbaru --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Invoice Terbaru</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-tool">
                            Lihat semua <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoice_terbaru as $inv)
                                <tr>
                                    <td>{{ $inv->nomor_invoice }}</td>
                                    <td>{{ $inv->pelanggan->nama ?? '-' }}</td>
                                    <td>Rp {{ number_format($inv->total_tagihan, 0, ',', '.') }}</td>
                                    <td>{{ $inv->status_pembayaran }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center" style="color:#9ca3af;">
                                        Belum ada data invoice.
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
