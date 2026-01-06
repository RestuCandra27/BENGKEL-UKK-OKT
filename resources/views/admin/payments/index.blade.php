<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0" style="color:#e5e7eb;">
                Verifikasi Pembayaran
            </h2>

            <form method="GET" action="{{ route('admin.payments.index') }}" class="d-flex align-items-center">
                <select name="status" class="form-control form-control-sm mr-2"
                        onchange="this.form.submit()">
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua</option>
                </select>
            </form>
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
                                @if ($p->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif ($p->status === 'confirmed')
                                    <span class="badge badge-success">Confirmed</span>
                                @else
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
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
