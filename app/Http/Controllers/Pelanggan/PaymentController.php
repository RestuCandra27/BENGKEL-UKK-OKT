<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(Request $request, $invoiceId)
    {
        $invoice = Invoice::with('payments', 'pelanggan')->findOrFail($invoiceId);

        // Pastikan invoice ini milik pelanggan yang login
        if ($invoice->user_id !== Auth::id()) {
            abort(403, 'Invoice ini bukan milik Anda.');
        }

        // Hitung sisa tagihan sebelum menerima input
        $totalBayarSebelumnya = $invoice->payments->sum('jumlah_bayar');
        $sisa                 = max($invoice->total_tagihan - $totalBayarSebelumnya, 0);

        // Jika sudah lunas, tidak boleh bayar lagi
        if ($sisa <= 0) {
            return back()->with('error', 'Invoice sudah lunas, tidak perlu melakukan pembayaran lagi.');
        }

        $request->validate([
            // SESUAIKAN dengan value di DB kamu (lihat kolom "metode_bayar" – di screenshot ada "Tunai" & "Transfer")
            'metode_bayar' => 'required|string|in:Tunai,Transfer,QRIS,Debit',
            'jumlah_bayar' => 'required|numeric|min:1000|max:' . $sisa,
            'bukti_bayar'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan'      => 'nullable|string|max:500',
        ], [
            'metode_bayar.in' => 'Metode pembayaran tidak valid.',
            'jumlah_bayar.max' => 'Jumlah tidak boleh melebihi sisa tagihan.',
        ]);

        // Simpan file bukti kalau ada
        $buktiPath = null;
        if ($request->hasFile('bukti_bayar')) {
            $buktiPath = $request->file('bukti_bayar')
                ->store('bukti_pembayaran', 'public');
        }

        Payment::create([
            'invoice_id'    => $invoice->id,
            'pelanggan_id'  => Auth::id(),                  // kalau mau diisi
            'jumlah_bayar'  => $request->jumlah_bayar,
            'metode_bayar'  => $request->metode_bayar,
            'tanggal_bayar' => now()->toDateString(),
            'catatan'       => $request->catatan,
            'bukti_path'    => $buktiPath,
            'status'        => 'pending',                   // ⬅️ pakai kolom "status"
        ]);

        return back()->with('success', 'Pembayaran berhasil dikirim. Menunggu verifikasi kasir/admin.');
    }
}
