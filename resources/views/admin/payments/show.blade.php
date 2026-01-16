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
                Metode Pembayaran:
                <strong>{{ $payment->metode_bayar }}</strong>
            </p>

            <p class="mb-1">
                Jumlah Dibayar:
                <strong>Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</strong>
            </p>

            <p class="mb-1">
                Tanggal Pembayaran:
                <strong>{{ $payment->tanggal_bayar }}</strong>
            </p>

            <p class="mb-1">
                Status:
                <span class="badge badge-success">
                    Lunas
                </span>
            </p>

            @if ($payment->catatan)
                <p class="mb-1">
                    Catatan:
                    {{ $payment->catatan }}
                </p>
            @endif

            <hr>

            {{-- INFORMASI TAMBAHAN --}}
            <p class="text-muted mb-0">
                Pembayaran ini telah dicatat oleh admin dan dianggap sah.
                Tidak diperlukan proses verifikasi lanjutan.
            </p>

        </div>
    </div>
</x-app-layout>
