<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Invoice
        </h2>
    </x-slot>

    <div class="card mt-3">
        <div class="card-body">

            {{-- FILTER --}}
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="belum_bayar" {{ ($status ?? '') === 'belum_bayar' ? 'selected' : '' }}>
                            Belum Bayar
                        </option>
                        <option value="dp" {{ ($status ?? '') === 'dp' ? 'selected' : '' }}>
                            DP
                        </option>
                        <option value="lunas" {{ ($status ?? '') === 'lunas' ? 'selected' : '' }}>
                            Lunas
                        </option>
                        <option value="all" {{ ($status ?? '') === 'all' ? 'selected' : '' }}>
                            Semua
                        </option>
                    </select>
                </div>

                <div class="col-md-4">
                    <input
                        type="text"
                        name="q"
                        value="{{ $keyword ?? '' }}"
                        class="form-control"
                        placeholder="Cari nomor invoice / nama pelanggan"
                    >
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        Filter
                    </button>
                </div>
            </form>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
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
                            @php
                                $totalBayar  = $invoice->payments->sum('jumlah_bayar');
                                $sisaTagihan = max($invoice->total_tagihan - $totalBayar, 0);
                            @endphp
                            <tr>
                                <td>{{ $invoice->nomor_invoice }}</td>
                                <td>{{ $invoice->pelanggan->nama ?? '-' }}</td>
                                <td>Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</td>
                                <td>
                                    @if ($totalBayar == 0)
                                        <span class="badge bg-secondary">Belum Bayar</span>
                                    @elseif ($sisaTagihan > 0)
                                        <span class="badge bg-warning">DP</span>
                                    @else
                                        <span class="badge bg-success">Lunas</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.invoices.show', $invoice->id) }}"
                                       class="btn btn-sm btn-info">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Tidak ada data invoice.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
