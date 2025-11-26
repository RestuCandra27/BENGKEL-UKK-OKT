<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    // Daftar semua invoice (kasir)
    public function index()
    {
        $invoices = Invoice::with('pelanggan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kasir.invoices.index', compact('invoices'));
    }

    // Detail invoice + form pembayaran (kasir)
    public function show($id)
{
    $invoice = Invoice::with(['pelanggan', 'servis', 'payments'])->findOrFail($id);

    $totalBayar   = $invoice->payments->sum('jumlah_bayar');
    $sisaTagihan  = max($invoice->total_tagihan - $totalBayar, 0);

    return view('kasir.invoices.show', compact('invoice', 'totalBayar', 'sisaTagihan'));
}

}
