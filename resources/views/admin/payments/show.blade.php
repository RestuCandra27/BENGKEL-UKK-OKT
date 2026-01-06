<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0" style="color:#e5e7eb;">
                Detail Pembayaran
            </h2>

            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary btn-sm">
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

            {{-- INFO INVOICE & PELANGGAN --}}
            <h5>Invoice: {{ $payment->invoice->nomor_invoice ?? '-' }}</h5>

            <p class="mb-1">
                Pelanggan:
                <strong>{{ $payment->invoice->pelanggan->nama ?? '-' }}</strong>
            </p>
            <p class="mb-1">
                Metode:
                <strong>{{ $payment->metode_bayar }}</strong>
            </p>
            <p class="mb-1">
                Jumlah:
                <strong>Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</strong>
            </p>
            <p class="mb-1">
                Tanggal Bayar:
                <strong>{{ $payment->tanggal_bayar }}</strong>
            </p>
            <p class="mb-1">
                Status:
                @if ($payment->status === 'pending')
                    <span class="badge badge-warning">Pending</span>
                @elseif ($payment->status === 'confirmed')
                    <span class="badge badge-success">Confirmed</span>
                @else
                    <span class="badge badge-danger">Rejected</span>
                @endif
            </p>

            @if ($payment->catatan)
                <p class="mb-1">
                    Catatan Pelanggan: {{ $payment->catatan }}
                </p>
            @endif

            @if ($payment->bukti_path)
                <div class="mt-3">
                    <h6>Bukti Pembayaran</h6>
                    @if (Str::endsWith(strtolower($payment->bukti_path), ['.jpg', '.jpeg', '.png']))
                        <img src="{{ asset('storage/'.$payment->bukti_path) }}"
                             alt="Bukti Pembayaran"
                             style="max-width:300px;border-radius:.5rem;">
                    @else
                        <a href="{{ asset('storage/'.$payment->bukti_path) }}"
                           target="_blank">
                            Lihat file bukti (PDF)
                        </a>
                    @endif
                </div>
            @endif

            @if ($payment->catatan_admin)
                <div class="mt-3">
                    <h6>Catatan Admin</h6>
                    <p class="mb-0">{{ $payment->catatan_admin }}</p>
                </div>
            @endif

            <hr>

            {{-- FORM VERIFIKASI / TOLAK HANYA JIKA MASIH PENDING --}}
            @if ($payment->status === 'pending')
                <div class="row">
                    <div class="col-md-6">
                        <h5>Verifikasi Pembayaran</h5>
                        <form method="POST" action="{{ route('admin.payments.verify', $payment->id) }}">
                            @csrf
                            <div class="form-group">
                                <label for="catatan_admin_verify">Catatan Admin (opsional)</label>
                                <textarea name="catatan_admin" id="catatan_admin_verify" rows="2"
                                          class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">
                                Verifikasi (Setujui)
                            </button>
                        </form>
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <h5>Tolak Pembayaran</h5>
                        <form method="POST" action="{{ route('admin.payments.reject', $payment->id) }}">
                            @csrf
                            <div class="form-group">
                                <label for="catatan_admin_reject">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="catatan_admin" id="catatan_admin_reject" rows="2"
                                          class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger">
                                Tolak Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <p class="text-muted mt-2 mb-0">
                    Pembayaran ini sudah diproses (status: {{ ucfirst($payment->status) }}).
                </p>
            @endif

        </div>
    </div>
</x-app-layout>
