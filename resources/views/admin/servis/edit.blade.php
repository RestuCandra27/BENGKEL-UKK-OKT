<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                Proses Servis: {{ $servis->kendaraan->no_polisi }}
            </h2>
            <a href="{{ route('admin.servis.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </x-slot>

    {{-- FLASH MESSAGE --}}
    @if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    <div class="row mt-3">
        {{-- KOLOM KIRI: INFO SERVIS & STATUS --}}
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Info Pelanggan & Unit</h3>
                </div>
                <div class="card-body">
                    <strong>Nama Pelanggan</strong>
                    <p class="text-muted">
                        {{ $servis->pelanggan->nama }}
                        ({{ $servis->pelanggan->no_hp ?? '-' }})
                    </p>

                    <strong>Kendaraan</strong>
                    <p class="text-muted">
                        {{ $servis->kendaraan->merek }} {{ $servis->kendaraan->model }} <br>
                        {{ $servis->kendaraan->no_polisi }}
                    </p>

                    <strong>Montir</strong>
                    <p class="text-muted">{{ $servis->montir->nama }}</p>

                    <hr>

                    {{-- FORM UPDATE STATUS --}}
                    <form action="{{ route('admin.servis.update', $servis->id) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="form-group">
                            <label>Keluhan</label>
                            <textarea name="keluhan" class="form-control" rows="3">{{ $servis->keluhan }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Status Pengerjaan</label>

                            @if ($servis->status_servis === 'dibayar')
                            <input type="text" class="form-control" value="Dibayar (Lunas)" disabled>
                            <input type="hidden" name="status_servis" value="dibayar">
                            @else
                            <select name="status_servis" class="form-control">
                                <option value="menunggu" {{ $servis->status_servis == 'menunggu' ? 'selected' : '' }}>
                                    Menunggu
                                </option>
                                <option value="dikerjakan" {{ $servis->status_servis == 'dikerjakan' ? 'selected' : '' }}>
                                    Sedang Dikerjakan
                                </option>
                                <option value="selesai" {{ $servis->status_servis == 'selesai' ? 'selected' : '' }}>
                                    Selesai (Siap Bayar)
                                </option>
                                <option value="dibatalkan" {{ $servis->status_servis == 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan
                                </option>
                            </select>
                            @endif
                        </div>


                        <button type="submit" class="btn btn-primary btn-block">Update Status</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: DETAIL BIAYA (JASA & BARANG) --}}
        <div class="col-md-8">

            {{-- TABEL JASA SERVIS --}}
            <div class="card card-outline card-success mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Jasa Servis</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama Layanan</th>
                                <th class="text-right">Biaya</th>
                                <th class="text-center" style="width: 50px">Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($servis->detail_layanans as $layanan)
                            <tr>
                                <td>{{ $layanan->nama_layanan }}</td>
                                <td class="text-right">
                                    Rp {{ number_format($layanan->biaya_standar, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.servis.layanan.destroy', [$servis->id, $layanan->id]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Hapus jasa ini?')">x</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach

                            {{-- Form Tambah Layanan --}}
                            <tr class="bg-light">
                                <form action="{{ route('admin.servis.layanan.store', $servis->id) }}" method="POST">
                                    @csrf
                                    <td colspan="2">
                                        <select name="layanan_id" class="form-control form-control-sm" required>
                                            <option value="">+ Tambah Jasa...</option>
                                            @foreach($all_layanans as $l)
                                            <option value="{{ $l->id }}">
                                                {{ $l->nama_layanan }} - Rp {{ number_format($l->biaya_standar) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </form>
                            </tr>
                            {{-- Form Tambah Paket Servis --}}
                            <tr class="bg-light">
                                <form action="{{ route('admin.servis.layanan.store', $servis->id) }}" method="POST">
                                    @csrf
                                    <td colspan="2">
                                        <select name="paket_servis_id" class="form-control form-control-sm">
                                            <option value="">+ Tambah Paket Servis...</option>
                                            @foreach($all_paket_servis as $p)
                                            <option value="{{ $p->id }}">
                                                {{ $p->nama_paket ?? 'Paket #' . $p->id }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </form>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TABEL SPAREPART --}}
            <div class="card card-outline card-warning mb-3">
                <div class="card-header">
                    <h3 class="card-title">Sparepart Digunakan</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Harga Satuan</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Subtotal</th>
                                <th class="text-center" style="width: 50px">Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($servis->detail_spareparts as $part)
                            <tr>
                                <td>{{ $part->nama_sparepart }}</td>
                                <td>
                                    Rp {{ number_format($part->pivot->harga_saat_digunakan, 0, ',', '.') }}
                                </td>
                                <td class="text-center">{{ $part->pivot->jumlah_digunakan }}</td>
                                <td class="text-right">
                                    Rp {{ number_format($part->pivot->harga_saat_digunakan * $part->pivot->jumlah_digunakan, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.servis.sparepart.destroy', [$servis->id, $part->id]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Hapus sparepart ini? Stok akan dikembalikan.')">x</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach

                            {{-- Form Tambah Sparepart --}}
                            <tr class="bg-light">
                                <form action="{{ route('admin.servis.sparepart.store', $servis->id) }}" method="POST">
                                    @csrf
                                    <td colspan="2">
                                        <select name="sparepart_id" class="form-control form-control-sm" required>
                                            <option value="">+ Tambah Sparepart...</option>
                                            @foreach($all_spareparts as $s)
                                            <option value="{{ $s->id }}">
                                                {{ $s->nama_sparepart }} ({{ $s->kode_sku }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="jumlah" class="form-control form-control-sm" placeholder="Qty" min="1" value="1" required>
                                    </td>
                                    <td></td>
                                    <td class="text-center">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TOTAL BIAYA --}}
            <div class="card bg-dark text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">TOTAL BIAYA</h4>
                    <h2 class="mb-0 font-weight-bold">
                        Rp {{ number_format($servis->total_biaya, 0, ',', '.') }}
                    </h2>
                </div>
            </div>

            @php
            $adaLayanan = $servis->detail_layanans && $servis->detail_layanans->count() > 0;
            $adaSparepart = $servis->detail_spareparts && $servis->detail_spareparts->count() > 0;
            @endphp

            {{-- TOMBOL BUAT INVOICE / PESAN PERINGATAN --}}
            @if ($adaLayanan || $adaSparepart)
            <form action="{{ route('admin.invoices.store') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="servis_id" value="{{ $servis->id }}">
                <button type="submit" class="btn btn-success">
                    Buat Invoice
                </button>
            </form>
            @else
            <div class="alert alert-warning mt-3">
                Tidak dapat membuat invoice karena belum ada layanan atau sparepart
                yang dipilih pada servis ini. Silakan tambahkan minimal satu layanan
                atau sparepart terlebih dahulu.
            </div>
            @endif

        </div>
    </div>
</x-app-layout>