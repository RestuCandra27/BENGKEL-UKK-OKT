<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight m-0">
                {{ __('Dashboard Kasir') }}
            </h2>
        </div>
    </x-slot>

    {{-- RINGKASAN STATISTIK --}}
    <div class="row mt-3">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card bg-dark text-white shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Invoice Belum Lunas</div>
                        <div class="h3 mb-0">{{ $totalBelumLunas }}</div>
                    </div>
                    <i class="fas fa-file-invoice-dollar fa-2x text-secondary"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card bg-dark text-white shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Invoice Lunas</div>
                        <div class="h3 mb-0">{{ $totalLunas }}</div>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL INVOICE TERBARU --}}
    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Invoice Terbaru</h3>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>No Invoice</th>
                        <th>Pelanggan</th>
                        <th>Total Tagihan</th>
                        <th>Status</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->nomor_invoice }}</td>
                            <td>{{ $invoice->pelanggan->nama ?? '-' }}</td>
                            <td>Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $invoice->status_pembayaran === 'Lunas' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $invoice->status_pembayaran }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('kasir.invoices.show', $invoice->id) }}"
                                   class="btn btn-sm btn-info">
                                    Detail / Bayar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada invoice.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
