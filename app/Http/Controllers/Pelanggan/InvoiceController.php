<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Daftar semua invoice milik pelanggan yang login.
     */
    public function index()
    {
        $user = Auth::user();

        $invoices = Invoice::with(['pelanggan', 'servis'])
            ->where('user_id', $user->id)     // Hanya invoice milik pelanggan ini
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pelanggan.invoices.index', compact('invoices'));
    }

    /**
     * Detail 1 invoice milik pelanggan.
     * Sekaligus tampilkan riwayat pembayaran.
     */
    public function show($id)
    {
        $user = Auth::user();

        // Pastikan invoice memang milik pelanggan yang login
        $invoice = Invoice::with(['pelanggan', 'servis.kendaraan', 'payments'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $totalBayar  = $invoice->payments->sum('jumlah_bayar');
        $sisaTagihan = max($invoice->total_tagihan - $totalBayar, 0);

        return view('pelanggan.invoices.show', compact('invoice', 'totalBayar', 'sisaTagihan'));
    }
}
