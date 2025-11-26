<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class PaymentController extends Controller
{
    /** INPUT PEMBAYARAN MANUAL */
    public function store(Request $request, Invoice $invoice)
{
    // 🔐 HANYA KASIR YANG BOLEH INPUT PEMBAYARAN
    $user = Auth::user();   // ambil user yang sedang login

    if (!$user || $user->role !== 'kasir') {
        abort(403, 'Hanya kasir yang boleh menginput pembayaran.');
    }

    $request->validate([
        'jumlah_bayar'  => 'required|numeric|min:0',
        'metode_bayar'  => 'required|in:Tunai,Transfer,QRIS,Debit',
        'tanggal_bayar' => 'required|date',
    ]);

    Payment::create([
        'invoice_id'    => $invoice->id,
        'jumlah_bayar'  => $request->jumlah_bayar,
        'metode_bayar'  => $request->metode_bayar,
        'tanggal_bayar' => $request->tanggal_bayar,
    ]);

    // HITUNG TOTAL DIBAYAR (pakai jumlah_bayar)
    $totalBayar = Payment::where('invoice_id', $invoice->id)->sum('jumlah_bayar');

    // JIKA SUDAH LUNAS
    if ($totalBayar >= $invoice->total_tagihan) {
        $invoice->update([
            'status_pembayaran' => 'Lunas',
        ]);

        // update status servis juga (jaga-jaga kalau relasi null)
        if ($invoice->servis) {
            $invoice->servis->update([
                'status_servis' => 'dibayar',
            ]);
        }
    }

    return back()->with('success', 'Pembayaran berhasil ditambahkan.');
}

}
