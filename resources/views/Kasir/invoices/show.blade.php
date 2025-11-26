<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight m-0">
                {{ __('Detail Invoice (Kasir)') }}
            </h2>

            <a href="{{ route('kasir.invoices.index') }}" class="btn btn-secondary btn-sm">
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

            @php
                $jenisMember = $invoice->pelanggan->jenis_member ?? 'Reguler';
                $subtotal    = ($invoice->total_biaya_layanan ?? 0) + ($invoice->total_biaya_sparepart ?? 0);
                $totalDiskon = max($subtotal - $invoice->total_tagihan, 0);
            @endphp

            <p>
                Pelanggan:
                <strong>{{ $invoice->pelanggan->nama ?? '-' }}</strong>
                <span class="badge bg-info ms-2">
                    Member: {{ $jenisMember }}
                </span>
            </p>

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

            @if ($totalBayar > 0 && $sisaTagihan > 0)
                <p>
                    <span class="badge bg-info">
                        Pelanggan sudah membayar DP Rp {{ number_format($totalBayar, 0, ',', '.') }}
                    </span>
                </p>
            @endif

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

            <hr>

            {{-- FORM PEMBAYARAN (HANYA JIKA BELUM LUNAS / MASIH ADA SISA) --}}
            @if ($sisaTagihan > 0 && $invoice->status_pembayaran !== 'Lunas')
                <h5>Tambah Pembayaran</h5>

                <form action="{{ route('kasir.invoices.payments.store', $invoice->id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>Jumlah Bayar</label>
                        <input type="number" name="jumlah_bayar" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Metode Bayar</label>
                        <select name="metode_bayar" class="form-control" required>
                            <option value="Tunai">Tunai</option>
                            <option value="Transfer">Transfer</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Debit">Debit</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar" class="form-control"
                               value="{{ date('Y-m-d') }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Pembayaran
                    </button>
                </form>
            @else
                <p class="text-muted mt-2 mb-0">
                    @if ($invoice->status_pembayaran === 'Lunas')
                        Invoice sudah lunas. Tidak dapat menambah pembayaran lagi.
                    @else
                        Tidak ada sisa tagihan.
                    @endif
                </p>
            @endif

        </div>
    </div>
</x-app-layout>
