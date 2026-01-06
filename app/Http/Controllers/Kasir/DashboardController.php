<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama untuk kasir
        $totalInvoice        = Invoice::count();
        $invoiceLunas        = Invoice::where('status_pembayaran', 'Lunas')->count();
        $invoiceBelumLunas   = Invoice::where('status_pembayaran', 'Belum Lunas')->count();

        // Pemasukan (berdasarkan tabel payments)
        $pemasukanHariIni = Payment::whereDate('tanggal_bayar', today())
            ->sum('jumlah_bayar');

        $pemasukanBulanIni = Payment::whereMonth('tanggal_bayar', now()->month)
            ->whereYear('tanggal_bayar', now()->year)
            ->sum('jumlah_bayar');

        // Invoice terbaru
        $invoiceTerbaru = Invoice::with('pelanggan')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Pembayaran terbaru
        $pembayaranTerbaru = Payment::with(['invoice.pelanggan'])
            ->orderBy('tanggal_bayar', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('kasir.dashboard', [
            'total_invoice'        => $totalInvoice,
            'invoice_lunas'        => $invoiceLunas,
            'invoice_belum_lunas'  => $invoiceBelumLunas,
            'pemasukan_hari_ini'   => $pemasukanHariIni,
            'pemasukan_bulan_ini'  => $pemasukanBulanIni,
            'invoice_terbaru'      => $invoiceTerbaru,
            'pembayaran_terbaru'   => $pembayaranTerbaru,
        ]);
    }
}
