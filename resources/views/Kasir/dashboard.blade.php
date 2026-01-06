<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kasir') }}
        </h2>
    </x-slot>

    {{-- ROW 1: Kartu statistik utama --}}
    <div class="row">
        <!-- Total Invoice -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $total_invoice }}</h3>
                    <p>Total Invoice</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <a href="{{ route('kasir.invoices.index') }}" class="small-box-footer">
                    Lihat semua invoice <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Invoice Lunas -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $invoice_lunas }}</h3>
                    <p>Invoice Lunas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="{{ route('kasir.invoices.index') }}" class="small-box-footer">
                    Kelola invoice <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Invoice Belum Lunas -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $invoice_belum_lunas }}</h3>
                    <p>Invoice Belum Lunas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <a href="{{ route('kasir.invoices.index') }}" class="small-box-footer">
                    Lihat tagihan pending <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Pemasukan Hari Ini -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>Rp {{ number_format($pemasukan_hari_ini, 0, ',', '.') }}</h3>
                    <p>Pemasukan Hari Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-coins"></i>
                </div>
                <a href="{{ route('kasir.invoices.index') }}" class="small-box-footer">
                    Detail pembayaran <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- ROW 2: Omzet bulanan + ringkasan --}}
    <div class="row">
        <!-- Pemasukan Bulan Ini -->
        <div class="col-lg-6">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Pemasukan Bulan Ini</h3>
                </div>
                <div class="card-body">
                    <h2 class="mb-0">
                        Rp {{ number_format($pemasukan_bulan_ini, 0, ',', '.') }}
                    </h2>
                    <small class="text-muted">
                        Total semua pembayaran yang tercatat di bulan {{ now()->format('F Y') }}.
                    </small>
                </div>
            </div>
        </div>

        <!-- Ringkasan Singkat -->
        <div class="col-lg-6">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan Transaksi</h3>
                </div>
                <div class="card-body">
                    <p class="mb-1">
                        • Total invoice: <strong>{{ $total_invoice }}</strong>
                    </p>
                    <p class="mb-1">
                        • Invoice lunas: <strong>{{ $invoice_lunas }}</strong>
                    </p>
                    <p class="mb-1">
                        • Invoice belum lunas: <strong>{{ $invoice_belum_lunas }}</strong>
                    </p>
                    <p class="mb-0">
                        • Pemasukan hari ini:
                        <strong>Rp {{ number_format($pemasukan_hari_ini, 0, ',', '.') }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 3: Tabel ringkasan --}}
    <div class="row">
        <!-- Invoice Terbaru -->
        <div class="col-lg-6">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Invoice Terbaru</h3>
                    <div class="card-tools">
                        <a href="{{ route('kasir.invoices.index') }}" class="btn btn-tool">
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
                                    <td colspan="4" class="text-center text-muted">
                                        Belum ada data invoice.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pembayaran Terbaru -->
        <div class="col-lg-6">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Pembayaran Terbaru</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pembayaran_terbaru as $pay)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($pay->tanggal_bayar)->format('d-m-Y') }}</td>
                                    <td>{{ $pay->invoice->pelanggan->nama ?? '-' }}</td>
                                    <td>{{ $pay->metode_bayar }}</td>
                                    <td>Rp {{ number_format($pay->jumlah_bayar, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Belum ada pembayaran tercatat.
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
