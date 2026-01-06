{{-- resources/views/admin/invoices/nota.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Pembayaran - {{ $invoice->nomor_invoice }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap ringan untuk styling cetak --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        body {
            font-size: 0.85rem;
        }
        .nota-wrapper {
            max-width: 900px;
            margin: 20px auto;
            padding: 16px 24px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        .table th, .table td {
            padding: .4rem .5rem;
        }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff; }
            .nota-wrapper {
                border: none;
                margin: 0;
                border-radius: 0;
            }
        }
    </style>
</head>
@php
    $servis    = $invoice->servis;
    $pelanggan = $invoice->pelanggan;

    // jaga-jaga kalau relasi kosong
    $detailLayanans = $servis->detail_layanans ?? collect();
    $spareparts     = $servis->spareparts ?? collect();
@endphp
<body>

<div class="nota-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0">Candra Garage</h5>
            <small>
                Nota Pembayaran Servis<br>
                Tanggal: {{ $invoice->created_at?->format('d/m/Y H:i') ?? '-' }}
            </small>
        </div>
        <div class="text-right">
            <h6 class="mb-0">Invoice</h6>
            <strong>{{ $invoice->nomor_invoice }}</strong><br>
            <small>Status:
                <span class="badge badge-{{ $invoice->status_pembayaran === 'Lunas' ? 'success' : 'warning' }}">
                    {{ $invoice->status_pembayaran }}
                </span>
            </small>
        </div>
    </div>

    <hr>

    {{-- DATA PELANGGAN & KENDARAAN --}}
    <div class="row mb-2">
        <div class="col-6">
            <h6>Pelanggan</h6>
            <table class="table table-sm table-borderless mb-1">
                <tr>
                    <th style="width: 30%;">Nama</th>
                    <td>: {{ $pelanggan->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Telp</th>
                    <td>: {{ $pelanggan->no_telepon ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Member</th>
                    @php
                        $jenisMember = $pelanggan->jenis_member ?? 'Reguler';
                    @endphp
                    <td>: {{ $jenisMember }}</td>
                </tr>
            </table>
        </div>
        <div class="col-6">
            <h6>Data Servis</h6>
            <table class="table table-sm table-borderless mb-1">
                <tr>
                    <th style="width: 40%;">Tanggal Servis</th>
                    <td>: {{ $servis->tanggal_servis?->format('d/m/Y') ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status Servis</th>
                    <td>: {{ ucfirst($servis->status_servis ?? '-') }}</td>
                </tr>
                <tr>
                    <th>No. Polisi</th>
                    <td>: {{ $servis->kendaraan->no_polisi ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- TABEL JASA / LAYANAN --}}
    <h6 class="mt-3">Rincian Jasa Servis</h6>
    <table class="table table-sm table-bordered">
        <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th>Deskripsi Layanan</th>
            <th style="width: 20%;" class="text-right">Biaya</th>
        </tr>
        </thead>
        <tbody>
        @php $totalLayananView = 0; @endphp
        @forelse ($detailLayanans as $detail)
            @php
                // Kita TIDAK lagi memanggil $detail->layanan.
                // Ambil nama & biaya dari field yang ada saja.
                $namaLayanan = $detail->nama_layanan
                    ?? ($detail->judul ?? $detail->deskripsi ?? '-');

                // coba biaya_standar, kalau tidak ada pakai nilai 0
                $biaya = $detail->biaya_standar
                    ?? ($detail->harga ?? 0);

                $totalLayananView += $biaya;
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $namaLayanan }}</td>
                <td class="text-right">
                    Rp {{ number_format($biaya, 0, ',', '.') }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">Tidak ada data layanan.</td>
            </tr>
        @endforelse
        </tbody>
        <tfoot>
        <tr>
            <th colspan="2" class="text-right">Total Jasa</th>
            <th class="text-right">
                Rp {{ number_format($invoice->total_biaya_layanan ?? $totalLayananView, 0, ',', '.') }}
            </th>
        </tr>
        </tfoot>
    </table>

    {{-- TABEL SPAREPART --}}
    <h6 class="mt-3">Rincian Sparepart</h6>
    <table class="table table-sm table-bordered">
        <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th>Nama Sparepart</th>
            <th style="width: 12%;" class="text-center">Qty</th>
            <th style="width: 18%;" class="text-right">Harga Satuan</th>
            <th style="width: 20%;" class="text-right">Sub Total</th>
        </tr>
        </thead>
        <tbody>
        @php $totalSparepartView = 0; @endphp
        @forelse ($spareparts as $sp)
            @php
                $qty   = $sp->pivot->jumlah       ?? 0;
                $harga = $sp->pivot->harga_satuan ?? 0;
                $sub   = $qty * $harga;
                $totalSparepartView += $sub;
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $sp->nama_sparepart ?? '-' }}</td>
                <td class="text-center">{{ $qty }}</td>
                <td class="text-right">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($sub, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data sparepart.</td>
            </tr>
        @endforelse
        </tbody>
        <tfoot>
        <tr>
            <th colspan="4" class="text-right">Total Sparepart</th>
            <th class="text-right">
                Rp {{ number_format($invoice->total_biaya_sparepart ?? $totalSparepartView, 0, ',', '.') }}
            </th>
        </tr>
        </tfoot>
    </table>

    {{-- RINGKASAN TOTAL + DISKON + PEMBAYARAN --}}
    @php
        $subtotal   = ($invoice->total_biaya_layanan ?? 0) + ($invoice->total_biaya_sparepart ?? 0);
        $diskon     = max($subtotal - $invoice->total_tagihan, 0);
        $totalBayar = $invoice->payments->sum('jumlah_bayar');
        $sisa       = max($invoice->total_tagihan - $totalBayar, 0);
    @endphp

    <div class="row mt-3">
        <div class="col-6">
            <h6>Catatan</h6>
            <p class="mb-1">
                Terima kasih telah melakukan servis di Candra Garage.
            </p>
            @if ($sisa > 0)
                <p class="mb-0">
                    Sisa tagihan harap dilunasi sesuai kesepakatan.
                </p>
            @else
                <p class="mb-0">
                    Tagihan ini telah <strong>LUNAS</strong>.
                </p>
            @endif
        </div>
        <div class="col-6">
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <th class="text-right" style="width: 50%;">Subtotal</th>
                    <td class="text-right">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <th class="text-right">Diskon Member</th>
                    <td class="text-right">
                        - Rp {{ number_format($diskon, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <th class="text-right">Total Tagihan</th>
                    <td class="text-right">
                        <strong>Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</strong>
                    </td>
                </tr>
                <tr>
                    <th class="text-right">Total Dibayar</th>
                    <td class="text-right">
                        Rp {{ number_format($totalBayar, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <th class="text-right">Sisa Tagihan</th>
                    <td class="text-right">
                        <strong>
                            @if ($sisa > 0)
                                Rp {{ number_format($sisa, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </strong>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="text-center mt-3 no-print">
        <button class="btn btn-primary btn-sm" onclick="window.print()">
            Cetak / Print
        </button>
    </div>
</div>

</body>
</html>
