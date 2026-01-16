<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0" style="color:#e5e7eb;">
                Riwayat Pembayaran
            </h2>

            <span class="text-muted">
                Daftar seluruh pembayaran yang telah dicatat oleh admin
            </span>
        </div>
    </x-slot>

    <div class="card mt-3">
        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success mb-3">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $p)
                        <tr>
                            <td>{{ $p->invoice->nomor_invoice ?? '-' }}</td>
                            <td>{{ $p->invoice->pelanggan->nama ?? '-' }}</td>
                            <td>{{ $p->metode_bayar }}</td>
                            <td>Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-success">
                                    Lunas
                                </span>
                            </td>
                            <td>{{ $p->tanggal_bayar }}</td>
                            <td>
                                <a href="{{ route('admin.payments.show', $p->id) }}"
                                   class="btn btn-sm btn-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada data pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
