<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * LIST PEMBAYARAN (ADMIN / KASIR)
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending'); // default: hanya pending

        $query = Payment::with(['invoice.pelanggan'])
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $payments = $query->paginate(10);

        return view('admin.payments.index', compact('payments', 'status'));
    }

    /**
     * DETAIL SATU PEMBAYARAN
     */
    public function show(Payment $payment)
    {
        $payment->load(['invoice.pelanggan']);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * INPUT PEMBAYARAN OLEH KASIR (langsung terkonfirmasi)
     * route: kasir.invoices.payments.store
     */
    public function store(Request $request, Invoice $invoice)
    {
        $request->validate([
            'metode_bayar'  => 'required|string|max:50',
            'jumlah_bayar'  => 'required|numeric|min:1000',
            'tanggal_bayar' => 'nullable|date',
            'catatan'       => 'nullable|string|max:500',
        ]);

        $tanggal = $request->tanggal_bayar
            ? $request->tanggal_bayar
            : now()->toDateString();

        $payment = Payment::create([
            'invoice_id'    => $invoice->id,
            'pelanggan_id'  => $invoice->user_id,
            'metode_bayar'  => $request->metode_bayar,
            'jumlah_bayar'  => $request->jumlah_bayar,
            'tanggal_bayar' => $tanggal,
            'status'        => 'confirmed', // kasir sudah terima uang
            'catatan'       => $request->catatan,
        ]);

        // sinkron status invoice
        $this->syncInvoiceStatus($invoice);

        return back()->with('success', 'Pembayaran berhasil dicatat oleh kasir.');
    }

    /**
     * INPUT PEMBAYARAN OLEH PELANGGAN (mandiri, status pending)
     * route: pelanggan.invoices.payments.store
     */
    public function storeFromCustomer(Request $request, Invoice $invoice)
    {
        $request->validate([
            'metode_bayar' => 'required|string|max:50',
            'jumlah_bayar' => 'required|numeric|min:1000',
            'bukti_bayar'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan'      => 'nullable|string|max:500',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_bayar')) {
            $buktiPath = $request->file('bukti_bayar')
                ->store('bukti_pembayaran', 'public');
        }

        Payment::create([
            'invoice_id'    => $invoice->id,
            'pelanggan_id'  => $invoice->user_id,
            'metode_bayar'  => $request->metode_bayar,
            'jumlah_bayar'  => $request->jumlah_bayar,
            'tanggal_bayar' => now()->toDateString(),
            'status'        => 'pending',         // menunggu dicek admin/kasir
            'bukti_path'    => $buktiPath,
            'catatan'       => $request->catatan,
        ]);

        return back()->with('success', 'Pembayaran berhasil dikirim. Menunggu verifikasi admin/kasir.');
    }

    /**
     * VERIFIKASI (SETUJU) PEMBAYARAN OLEH ADMIN/KASIR
     * route: admin.payments.verify
     */
    public function verify(Request $request, Payment $payment)
    {
        if ($payment->status === 'confirmed') {
            return back()->with('success', 'Pembayaran ini sudah pernah diverifikasi.');
        }

        $request->validate([
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        $payment->status        = 'confirmed';
        $payment->catatan_admin = $request->catatan_admin;
        $payment->verified_by   = Auth::id();
        $payment->verified_at   = now();
        $payment->save();

        $this->syncInvoiceStatus($payment->invoice);

        return redirect()
            ->route('admin.payments.show', $payment->id)
            ->with('success', 'Pembayaran telah diverifikasi.');
    }

    /**
     * TOLAK PEMBAYARAN
     * route: admin.payments.reject
     */
    public function reject(Request $request, Payment $payment)
    {
        if ($payment->status === 'rejected') {
            return back()->with('success', 'Pembayaran ini sudah pernah ditolak.');
        }

        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ]);

        $payment->status        = 'rejected';
        $payment->catatan_admin = $request->catatan_admin;
        $payment->verified_by   = Auth::id();
        $payment->verified_at   = now();
        $payment->save();

        // kalau ditolak, tetap sinkron (mungkin semua payment confirmed=0)
        $this->syncInvoiceStatus($payment->invoice);

        return redirect()
            ->route('admin.payments.show', $payment->id)
            ->with('success', 'Pembayaran telah ditolak.');
    }

    /**
     * Helper: update status_pembayaran pada invoice
     */
    protected function syncInvoiceStatus(Invoice $invoice)
    {
        // hanya hitung payment yang confirmed
        $totalConfirmed = $invoice->payments()
            ->where('status', 'confirmed')
            ->sum('jumlah_bayar');

        if ($totalConfirmed >= $invoice->total_tagihan) {
            $invoice->status_pembayaran = 'Lunas';
        } elseif ($totalConfirmed > 0) {
            $invoice->status_pembayaran = 'Belum Lunas'; // bisa juga diubah ke 'DP'
        } else {
            $invoice->status_pembayaran = 'Belum Lunas';
        }

        $invoice->save();
    }
}
