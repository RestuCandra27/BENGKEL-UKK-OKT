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

            {{-- Flash message --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Info utama invoice --}}
            <h4>Invoice: {{ $invoice->nomor_invoice }}</h4>

            <p>
                Pelanggan:
                <strong>{{ $invoice->pelanggan->nama ?? '-' }}</strong>
            </p>

            <p>
                Kendaraan:
                <strong>
                    {{ $invoice->servis->kendaraan->no_polisi ?? '-' }}
                    ({{ $invoice->servis->kendaraan->merek ?? '' }}
                    {{ $invoice->servis->kendaraan->model ?? '' }})
                </strong>
            </p>

            <p>
                Total Tagihan:
                <strong>Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</strong>
            </p>

            <p>
                Status:
                <span class="badge {{ $invoice->status_pembayaran == 'Lunas' ? 'badge-success' : 'badge-warning' }}">
                    {{ $invoice->status_pembayaran }}
                </span>
            </p>

            <p>
                Total Dibayar:
                <strong>Rp {{ number_format($totalBayar, 0, ',', '.') }}</strong>
            </p>

            <p>
                Sisa Tagihan:
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

            <hr>

            {{-- Form pembayaran mandiri oleh pelanggan --}}
            @if ($invoice->status_pembayaran !== 'Lunas')
                <h5 class="mt-3">Kirim Pembayaran</h5>

                @php
                    // fallback kalau variabel belum ada
                    $sisa = $sisaTagihan ?? max($invoice->total_tagihan - $invoice->payments->sum('jumlah_bayar'), 0);
                @endphp

                <form method="POST"
                      action="{{ route('pelanggan.invoices.payments.store', $invoice->id) }}"
                      enctype="multipart/form-data">
                    @csrf

                    {{-- METODE BAYAR
                         PENTING: value HARUS sama dengan yang divalidasi di controller:
                         'Tunai', 'Transfer', 'QRIS', 'Debit'
                    --}}
                    <div class="form-group">
                        <label for="metode_bayar">Metode Pembayaran</label>
                        <select name="metode_bayar"
                                id="metode_bayar"
                                class="form-control @error('metode_bayar') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="Tunai" {{ old('metode_bayar') == 'Tunai' ? 'selected' : '' }}>
                                Tunai (Cash)
                            </option>
                            <option value="Transfer" {{ old('metode_bayar') == 'Transfer' ? 'selected' : '' }}>
                                Transfer Bank
                            </option>
                            <option value="QRIS" {{ old('metode_bayar') == 'QRIS' ? 'selected' : '' }}>
                                QRIS
                            </option>
                            <option value="Debit" {{ old('metode_bayar') == 'Debit' ? 'selected' : '' }}>
                                Kartu Debit/Kredit
                            </option>
                        </select>
                        @error('metode_bayar')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- JUMLAH BAYAR --}}
                    <div class="form-group">
                        <label for="jumlah_bayar">Jumlah Bayar</label>
                        <input type="number"
                               name="jumlah_bayar"
                               id="jumlah_bayar"
                               class="form-control @error('jumlah_bayar') is-invalid @enderror"
                               value="{{ old('jumlah_bayar', $sisa) }}"
                               min="1000"
                               required>
                        @error('jumlah_bayar')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Sisa tagihan: Rp {{ number_format($sisa, 0, ',', '.') }}
                        </small>
                    </div>

                    {{-- BUKTI TRANSFER --}}
                    <div class="form-group">
                        <label for="bukti_bayar">Upload Bukti Pembayaran (jpg, png, pdf)</label>
                        <input type="file"
                               name="bukti_bayar"
                               id="bukti_bayar"
                               class="form-control-file @error('bukti_bayar') is-invalid @enderror"
                               accept=".jpg,.jpeg,.png,.pdf">
                        @error('bukti_bayar')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Opsional, tapi dianjurkan untuk Transfer / QRIS / Debit.
                        </small>
                    </div>

                    {{-- CATATAN OPSIONAL --}}
                    <div class="form-group">
                        <label for="catatan">Catatan (opsional)</label>
                        <textarea name="catatan"
                                  id="catatan"
                                  rows="2"
                                  class="form-control @error('catatan') is-invalid @enderror"
                                  placeholder="Contoh: Transfer dari BCA a.n ...">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Kirim Pembayaran
                    </button>

                    <p class="text-muted mt-2 mb-0">
                        Setelah dikirim, pembayaran akan dicek oleh admin/kasir.
                        Status invoice akan menjadi <strong>Lunas</strong> setelah diverifikasi.
                    </p>
                </form>
            @else
                <p class="text-success mt-3 mb-0">
                    Invoice ini sudah <strong>Lunas</strong>. Terima kasih 🎉
                </p>
            @endif

        </div>
    </div>
</x-app-layout>
