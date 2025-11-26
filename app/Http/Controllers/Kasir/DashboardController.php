<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function index()
    {
        // Beberapa angka ringkas untuk kasir (opsional)
        $totalBelumLunas = Invoice::where('status_pembayaran', 'Belum Lunas')->count();
        $totalLunas      = Invoice::where('status_pembayaran', 'Lunas')->count();

        // Ambil beberapa invoice terbaru
        $invoices = Invoice::with('pelanggan')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('kasir.dashboard', compact('totalBelumLunas', 'totalLunas', 'invoices'));
    }
}
