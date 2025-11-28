<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Detail Invoice Saya') }}
            </h2>

            <a href="{{ route('pelanggan.invoices.index') }}" class="btn btn-secondary btn-sm">
                &laquo; Kembali
            </a>
        </div>
    </x-slot>

    <div class="card mt-3">
        <div class="card-body">

            {{-- Info utama invoice --}}
            <h4>Invoice: {{ $invoice->nomor_invoice }}</h4>

            <p>Pelanggan:
                <strong>{{ $invoice->pelanggan->nama ?? '-' }}</strong>
            </p>

            <p>Kendaraan:
                <strong>
                    {{ $invoice->servis->kendaraan->no_polisi ?? '-' }}
                    ({{ $invoice->servis->kendaraan->merek ?? '' }}
                    {{ $invoice->servis->kendaraan->model ?? '' }})
                </strong>
            </p>

            <p>Total Tagihan:
                <strong>Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</strong>
            </p>

            <p>Status:
                <span class="badge {{ $invoice->status_pembayaran == 'Lunas' ? 'badge-success' : 'badge-warning' }}">
                    {{ $invoice->status_pembayaran }}
                </span>
            </p>

            <p>Total Dibayar:
                <strong>Rp {{ number_format($totalBayar, 0, ',', '.') }}</strong>
            </p>

            <p>Sisa Tagihan:
                <strong>Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</strong>
            </p>

            <hr>

            {{-- Riwayat Pembayaran --}}
            <h5>Riwayat Pembayaran</h5>

            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoice->payments as $pay)
                        <tr>
                            <td>{{ $pay->tanggal_bayar }}</td>
                            <td>{{ $pay->metode_bayar }}</td>
                            <td>Rp {{ number_format($pay->jumlah_bayar, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                Belum ada pembayaran yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <p class="text-muted mt-3">
                *Silakan lakukan pembayaran di kasir. Setelah kasir menginput pembayaran,
                status invoice ini akan otomatis diperbarui.
            </p>
        </div>
    </div>
</x-app-layout>
