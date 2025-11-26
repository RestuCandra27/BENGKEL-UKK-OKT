<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight m-0">
                {{ __('Detail Invoice') }}
            </h2>

            <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary btn-sm">
                &laquo; Kembali
            </a>
        </div>
    </x-slot>

    <div class="card mt-3">
        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success mb-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mb-3">
                    {{ session('error') }}
                </div>
            @endif

            {{-- INFORMASI UTAMA INVOICE --}}
            <h4>Invoice: {{ $invoice->nomor_invoice }}</h4>

            <p>
                Pelanggan:
                <strong>{{ $invoice->pelanggan->nama ?? '-' }}</strong>
                @php
                    $jenisMember = $invoice->pelanggan->jenis_member ?? 'Reguler';
                @endphp
                <span class="badge bg-info ms-2">
                    Member: {{ $jenisMember }}
                </span>
            </p>

            @php
                $subtotal    = ( $invoice->total_biaya_layanan ?? 0 ) + ( $invoice->total_biaya_sparepart ?? 0 );
                $totalDiskon = max($subtotal - $invoice->total_tagihan, 0);
            @endphp

            <p>Subtotal (Jasa + Sparepart):
                <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
            </p>

            <p>Diskon Member ({{ $jenisMember }}):
                <strong>Rp {{ number_format($totalDiskon, 0, ',', '.') }}</strong>
            </p>

            <p>Total Tagihan (Setelah Diskon):
                <strong>Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</strong>
            </p>

            <p>Total Dibayar:
                <strong>Rp {{ number_format($totalBayar, 0, ',', '.') }}</strong>
            </p>

            <p>Sisa Tagihan:
                <strong>
                    @if ($sisaTagihan > 0)
                        Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </strong>
            </p>

            <p>Status:
                <span class="badge {{ $invoice->status_pembayaran == 'Lunas' ? 'bg-success' : 'bg-warning' }}">
                    {{ $invoice->status_pembayaran }}
                </span>
            </p>

            <hr>

            {{-- RIWAYAT PEMBAYARAN --}}
            <h5>Riwayat Pembayaran</h5>

            <table class="table">
                <thead>
                    <tr>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoice->payments as $pay)
                        <tr>
                            <td>{{ $pay->metode_bayar }}</td>
                            <td>Rp {{ number_format($pay->jumlah_bayar, 0, ',', '.') }}</td>
                            <td>{{ $pay->tanggal_bayar }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- ADMIN TIDAK BISA INPUT PEMBAYARAN --}}
            <p class="text-muted mt-2 mb-0">
                * Input pembayaran hanya dapat dilakukan melalui panel Kasir.
            </p>
        </div>
    </div>
</x-app-layout>
