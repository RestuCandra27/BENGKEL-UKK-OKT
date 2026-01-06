<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                Detail Servis: {{ $servis->kendaraan->no_polisi ?? '-' }}
            </h2>
            <a href="{{ route('montir.servis.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    {{-- ==========================
         BARIS 1 : INFO & JASA/SPAREPART
    =========================== --}}
    <div class="row mt-3">
        {{-- KOLOM KIRI: INFO & STATUS --}}
        <div class="col-md-4">
            <div class="card card-primary card-outline mb-3">
                <div class="card-header">
                    <h3 class="card-title">Info Pelanggan & Unit</h3>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Nama Pelanggan</strong>
                        <p class="text-muted mb-1">
                            {{ $servis->pelanggan->nama }}
                            ({{ $servis->pelanggan->no_hp ?? '-' }})
                        </p>
                    </div>

                    <div class="mb-2">
                        <strong>Kendaraan</strong>
                        <p class="text-muted mb-1">
                            {{ $servis->kendaraan->merek ?? '-' }}
                            {{ $servis->kendaraan->model ?? '' }}<br>
                            {{ $servis->kendaraan->no_polisi ?? '-' }}
                        </p>
                    </div>

                    <div class="mb-2">
                        <strong>Tanggal Servis</strong>
                        <p class="text-muted mb-1">
                            {{ \Carbon\Carbon::parse($servis->tanggal_servis)->format('d M Y') }}
                        </p>
                    </div>

                    <div class="mb-2">
                        <strong>Status Servis</strong><br>
                        @php
                            $badgeClass = 'badge-secondary';
                            if ($servis->status_servis === 'menunggu') $badgeClass = 'badge-warning';
                            elseif ($servis->status_servis === 'dikerjakan') $badgeClass = 'badge-info';
                            elseif ($servis->status_servis === 'selesai') $badgeClass = 'badge-success';
                            elseif ($servis->status_servis === 'dibayar') $badgeClass = 'badge-primary';
                            elseif ($servis->status_servis === 'dibatalkan') $badgeClass = 'badge-danger';
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            {{ ucfirst($servis->status_servis) }}
                        </span>
                    </div>

                    <hr>

                    {{-- FORM UPDATE STATUS OLEH MONTIR --}}
                    <form action="{{ route('montir.servis.update-status', $servis->id) }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label>Keluhan</label>
                            <textarea class="form-control" rows="3" readonly>{{ $servis->keluhan }}</textarea>
                        </div>

                        @if (!in_array($servis->status_servis, ['selesai', 'dibayar', 'dibatalkan']))
                            <button type="submit" class="btn btn-primary btn-block">
                                Update Status
                            </button>
                        @else
                            <p class="text-muted mt-2 mb-0">
                                Status sudah <strong>{{ $servis->status_servis }}</strong>, tidak bisa diubah lagi.
                            </p>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: JASA & SPAREPART --}}
        <div class="col-md-8">

            {{-- JASA SERVIS --}}
            <div class="card card-outline card-success mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Jasa Servis</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Nama Layanan</th>
                                <th class="text-right">Biaya</th>
                                <th class="text-center" style="width:50px;">Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($servis->detail_layanans as $layanan)
                                <tr>
                                    <td>{{ $layanan->nama_layanan }}</td>
                                    <td class="text-right">
                                        Rp {{ number_format($layanan->biaya_standar, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if (in_array($servis->status_servis, ['menunggu', 'dikerjakan']))
                                            <form action="{{ route('montir.servis.layanan.destroy', [$servis->id, $layanan->id]) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Hapus layanan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger">x</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        Belum ada jasa yang dicatat.
                                    </td>
                                </tr>
                            @endforelse

                            @if (in_array($servis->status_servis, ['menunggu', 'dikerjakan']))
                                {{-- Form tambah layanan --}}
                                <tr class="bg-light">
                                    <form action="{{ route('montir.servis.layanan.store', $servis->id) }}" method="POST">
                                        @csrf
                                        <td colspan="2">
                                            <select name="layanan_id" class="form-control form-control-sm" required>
                                                <option value="">+ Tambah Jasa...</option>
                                                @foreach($all_layanans as $l)
                                                    <option value="{{ $l->id }}">
                                                        {{ $l->nama_layanan }} — Rp {{ number_format($l->biaya_standar, 0, ',', '.') }}
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
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SPAREPART DIGUNAKAN --}}
            <div class="card card-outline card-warning mb-3">
                <div class="card-header">
                    <h3 class="card-title">Sparepart Digunakan</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th class="text-right">Harga Satuan</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Subtotal</th>
                                <th class="text-center" style="width:50px;">Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($servis->spareparts as $part)
                                <tr>
                                    <td>{{ $part->nama_sparepart }}</td>
                                    <td class="text-right">
                                        Rp {{ number_format($part->pivot->harga_satuan, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">{{ $part->pivot->jumlah }}</td>
                                    <td class="text-right">
                                        Rp {{ number_format($part->pivot->subtotal, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if (in_array($servis->status_servis, ['menunggu', 'dikerjakan']))
                                            <form action="{{ route('montir.servis.sparepart.destroy', [$servis->id, $part->id]) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Hapus sparepart ini? Stok akan dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger">x</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Belum ada sparepart yang dicatat.
                                    </td>
                                </tr>
                            @endforelse

                            @if (in_array($servis->status_servis, ['menunggu', 'dikerjakan']))
                                {{-- Form tambah sparepart --}}
                                <tr class="bg-light">
                                    <form action="{{ route('montir.servis.sparepart.store', $servis->id) }}" method="POST">
                                        @csrf
                                        <td>
                                            <select name="sparepart_id" class="form-control form-control-sm" required>
                                                <option value="">+ Tambah Sparepart...</option>
                                                @foreach($all_spareparts as $s)
                                                    <option value="{{ $s->id }}">
                                                        {{ $s->nama_sparepart }}
                                                        — Rp {{ number_format($s->harga_jual, 0, ',', '.') }}
                                                        (Stok: {{ $s->stok }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-right">
                                            <small class="text-muted">Harga ambil dari master sparepart</small>
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="jumlah" class="form-control form-control-sm"
                                                   value="1" min="1" required>
                                        </td>
                                        <td></td>
                                        <td class="text-center">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                    </form>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TOTAL BIAYA (ESTIMASI) --}}
            <div class="card bg-dark text-white mb-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">TOTAL BIAYA (Estimasi)</h4>
                    <h2 class="mb-0 font-weight-bold">
                        Rp {{ number_format($servis->total_biaya, 0, ',', '.') }}
                    </h2>
                </div>
            </div>

        </div>
    </div>

    {{-- ==========================
         BARIS 2 : CATATAN KONDISI (FULL WIDTH)
    =========================== --}}
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card card-outline card-secondary mb-3">
                <div class="card-header">
                    <h3 class="card-title">Catatan Kondisi & Rekomendasi</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('montir.servis.update-riwayat', $servis->id) }}" method="POST">
                        @csrf

                        <div class="form-group mb-2">
                            <label>Catatan Saat Masuk</label>
                            <textarea name="catatan_saat_masuk" class="form-control" rows="2">{{ old('catatan_saat_masuk', $servis->riwayat_kondisi->catatan_saat_masuk ?? '') }}</textarea>
                        </div>

                        <div class="form-group mb-2">
                            <label>Catatan Setelah Selesai</label>
                            <textarea name="catatan_setelah_selesai" class="form-control" rows="2">{{ old('catatan_setelah_selesai', $servis->riwayat_kondisi->catatan_setelah_selesai ?? '') }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label>Rekomendasi Montir</label>
                            <textarea name="rekomendasi_montir" class="form-control" rows="2">{{ old('rekomendasi_montir', $servis->riwayat_kondisi->rekomendasi_montir ?? '') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm">
                            Simpan Catatan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
