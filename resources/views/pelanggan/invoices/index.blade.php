<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoice & Tagihan Saya') }}
        </h2>
    </x-slot>

    <div class="card mt-3">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Tanggal</th>
                        <th>Kendaraan</th>
                        <th>Total Tagihan</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->nomor_invoice }}</td>
                            <td>{{ $invoice->created_at ? $invoice->created_at->format('d M Y') : '-' }}</td>
                            <td>
                                {{ $invoice->servis->kendaraan->no_polisi ?? '-' }}<br>
                                <small class="text-muted">
                                    {{ $invoice->servis->kendaraan->merek ?? '' }}
                                    {{ $invoice->servis->kendaraan->model ?? '' }}
                                </small>
                            </td>
                            <td>Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge
                                    {{ $invoice->status_pembayaran === 'Lunas' ? 'badge-success' : 'badge-warning' }}">
                                    {{ $invoice->status_pembayaran }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('pelanggan.invoices.show', $invoice->id) }}"
                                   class="btn btn-sm btn-info">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Belum ada invoice untuk Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $invoices->links() }}
        </div>
    </div>
</x-app-layout>
