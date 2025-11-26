<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Daftar Invoice
        </h2>
    </x-slot>

    <div class="card mt-3">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No Invoice</th>
                        <th>Pelanggan</th>
                        <th>Total Tagihan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->nomor_invoice }}</td>
                        <td>{{ $invoice->pelanggan->nama }}</td>
                        <td>Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge 
                                    {{ $invoice->status_pembayaran === 'Lunas' ? 'bg-success' : 'bg-warning' }}">
                                {{ $invoice->status_pembayaran }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.invoices.show', $invoice->id) }}"
                                class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada invoice.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $invoices->links() }}
        </div>
    </div>
</x-app-layout>