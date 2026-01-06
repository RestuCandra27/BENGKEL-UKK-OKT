<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Servis;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Statistik pelanggan
        $totalServis = Servis::where('user_id', $userId)->count();

        $servisAktif = Servis::where('user_id', $userId)
            ->whereIn('status_servis', ['menunggu','dikerjakan'])
            ->count();

        $invoiceBelumLunas = Invoice::where('user_id', $userId)
            ->where('status_pembayaran','Belum Lunas')
            ->count();

        // Riwayat servis terakhir
        $servisTerakhir = Servis::with('kendaraan')
            ->where('user_id', $userId)
            ->orderBy('created_at','desc')
            ->limit(5)
            ->get();

        // Invoice terakhir
        $invoiceTerakhir = Invoice::where('user_id', $userId)
            ->orderBy('created_at','desc')
            ->limit(5)
            ->get();

        return view('pelanggan.dashboard', [
            'total_servis'         => $totalServis,
            'servis_aktif'         => $servisAktif,
            'invoice_belum_lunas' => $invoiceBelumLunas,
            'servis_terakhir'     => $servisTerakhir,
            'invoice_terakhir'    => $invoiceTerakhir,
        ]);
    }
}
