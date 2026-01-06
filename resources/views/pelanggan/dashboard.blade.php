<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Pelanggan
        </h2>
    </x-slot>

    {{-- Statistik --}}
    <div class="row">

        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $total_servis }}</h3>
                    <p>Total Servis</p>
                </div>
                <div class="icon"><i class="fas fa-tools"></i></div>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $servis_aktif }}</h3>
                    <p>Servis Aktif</p>
                </div>
                <div class="icon"><i class="fas fa-wrench"></i></div>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $invoice_belum_lunas }}</h3>
                    <p>Invoice Belum Lunas</p>
                </div>
                <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
            </div>
        </div>

    </div>

    {{-- Servis Terakhir --}}
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">5 Servis Terakhir</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kendaraan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servis_terakhir as $s)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($s->tanggal_servis)->format('d-m-Y') }}</td>
                            <td>{{ $s->kendaraan->no_polisi ?? '-' }}</td>
                            <td>{{ ucfirst($s->status_servis) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Belum ada riwayat servis.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Invoice Terakhir --}}
    <div class="card card-outline card-success mt-3">
        <div class="card-header">
            <h3 class="card-title">5 Invoice Terakhir</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>No Invoice</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoice_terakhir as $inv)
                        <tr>
                            <td>{{ $inv->nomor_invoice }}</td>
                            <td>Rp {{ number_format($inv->total_tagihan,0,',','.') }}</td>
                            <td>{{ $inv->status_pembayaran }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Belum ada invoice.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
