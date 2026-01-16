<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * RIWAYAT PEMBAYARAN (ADMIN)
     */
    public function index()
    {
        $payments = Payment::with(['invoice.pelanggan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * DETAIL PEMBAYARAN (READ ONLY)
     */
    public function show(Payment $payment)
    {
        $payment->load(['invoice.pelanggan']);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * INPUT PEMBAYARAN OLEH ADMIN
     * - Bisa DP
     * - Tidak bisa melebihi sisa tagihan
     * - Tidak bisa input jika sudah lunas
     */
    public function store(Request $request, Invoice $invoice)
    {
        // ===== HITUNG REAL-TIME =====
        $totalBayar  = $invoice->payments()
            ->where('status', 'confirmed')
            ->sum('jumlah_bayar');

        $sisaTagihan = max($invoice->total_tagihan - $totalBayar, 0);

        // ❌ Jika sudah lunas, tolak
        if ($sisaTagihan <= 0) {
            return back()->with('error', 'Invoice ini sudah lunas. Tidak dapat menambahkan pembayaran.');
        }

        // ===== VALIDASI (ANTI BYPASS) =====
        $request->validate([
            'metode_bayar'  => 'required|string|max:50',
            'tanggal_bayar' => 'required|date|after_or_equal:today',
            'jumlah_bayar'  => [
                'required',
                'numeric',
                'min:1000',
                'max:' . $sisaTagihan, // ⬅️ KUNCI DP AMAN
            ],
            'catatan'       => 'nullable|string|max:500',
        ]);

        // ===== SIMPAN PEMBAYARAN =====
        Payment::create([
            'invoice_id'    => $invoice->id,
            'pelanggan_id'  => $invoice->user_id,
            'metode_bayar'  => $request->metode_bayar,
            'jumlah_bayar'  => $request->jumlah_bayar,
            'tanggal_bayar' => $request->tanggal_bayar,
            'status'        => 'confirmed', // ADMIN = LANGSUNG SAH
            'catatan'       => $request->catatan,
        ]);

        // ===== SINKRON STATUS INVOICE =====
        $this->syncInvoiceStatus($invoice);

        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }

    /**
     * Helper: Sinkron status invoice
     */
    protected function syncInvoiceStatus(Invoice $invoice)
    {
        $totalConfirmed = $invoice->payments()
            ->where('status', 'confirmed')
            ->sum('jumlah_bayar');

        if ($totalConfirmed >= $invoice->total_tagihan) {
            $invoice->status_pembayaran = 'Lunas';
        } else {
            $invoice->status_pembayaran = 'Belum Lunas';
        }

        $invoice->save();
    }
}
