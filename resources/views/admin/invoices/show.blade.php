<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight m-0">
                Detail Invoice
            </h2>

            <div>
                @if ($sisaTagihan == 0)
                    <a href="{{ route('admin.invoices.nota', $invoice->id) }}" class="btn btn-outline-primary btn-sm"
                        target="_blank">
                        Cetak Nota
                    </a>
                @else
                    <button class="btn btn-outline-secondary btn-sm" disabled title="Invoice belum lunas">
                        Cetak Nota
                    </button>
                @endif

                <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary btn-sm">
                    &laquo; Kembali
                </a>
            </div>
        </div>
    </x-slot>

    @php
        // ===== HITUNG REAL-TIME (ANTI DATA NYANGKUT) =====
        $totalBayar = $invoice->payments->sum('jumlah_bayar');
        $sisaTagihan = max($invoice->total_tagihan - $totalBayar, 0);

        $jenisMember = $invoice->pelanggan->jenis_member ?? 'Reguler';

        $subtotal = ($invoice->total_biaya_layanan ?? 0)
            + ($invoice->total_biaya_sparepart ?? 0);

        $totalDiskon = max($subtotal - $invoice->total_tagihan, 0);
    @endphp

    <div class="card mt-3">
        <div class="card-body">

            {{-- FLASH MESSAGE --}}
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

            {{-- INFORMASI INVOICE --}}
            <h4>Invoice: {{ $invoice->nomor_invoice }}</h4>

            <p>
                Pelanggan:
                <strong>{{ $invoice->pelanggan->nama ?? '-' }}</strong>
                <span class="badge bg-info ms-2">
                    Member: {{ $jenisMember }}
                </span>
            </p>

            <p>
                Subtotal (Jasa + Sparepart):
                <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
            </p>

            <p>
                Diskon Member ({{ $jenisMember }}):
                <strong>Rp {{ number_format($totalDiskon, 0, ',', '.') }}</strong>
            </p>

            <p>
                Total Tagihan (Setelah Diskon):
                <strong>Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</strong>
            </p>

            <p>
                Total Dibayar:
                <strong>Rp {{ number_format($totalBayar, 0, ',', '.') }}</strong>
            </p>

            <p>
                Sisa Tagihan:
                <strong>
                    @if ($sisaTagihan > 0)
                        Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                    @else
                        Rp 0
                    @endif
                </strong>
            </p>

            <p>
                Status:
                @if ($sisaTagihan == 0)
                    <span class="badge bg-success">Lunas</span>
                @else
                    <span class="badge bg-warning">Belum Lunas</span>
                @endif
            </p>

            <hr>

            {{-- RIWAYAT PEMBAYARAN --}}
            <h5>Riwayat Pembayaran</h5>

            <table class="table table-bordered">
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
                            <td colspan="3" class="text-center text-muted">
                                Belum ada pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- INPUT PEMBAYARAN (HANYA JIKA BELUM LUNAS) --}}
            @if ($sisaTagihan > 0)
                <hr>

                <h5 class="mt-4">Input Pembayaran (Admin)</h5>

                <form action="{{ route('admin.invoices.payments.store', $invoice->id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Tanggal Pembayaran</label>
                        <input type="date" name="tanggal_bayar" class="form-control" min="{{ now()->toDateString() }}"
                            value="{{ now()->toDateString() }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="metode_bayar" class="form-control" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="Tunai">Tunai</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Bayar</label>
                        <input type="number" name="jumlah_bayar" class="form-control" min="1000" max="{{ $sisaTagihan }}"
                            required>
                        <small class="text-muted">
                            Maksimal pembayaran:
                            Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                        </small>
                    </div>

                    <button type="submit" class="btn btn-success">
                        Simpan Pembayaran
                    </button>
                </form>
            @else
                <div class="alert alert-success mt-4">
                    Invoice ini sudah <strong>LUNAS</strong>.
                    Tidak dapat menambahkan pembayaran lagi.
                </div>
            @endif

        </div>
    </div>
</x-app-layout>