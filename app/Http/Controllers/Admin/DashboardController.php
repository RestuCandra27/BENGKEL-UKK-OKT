<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Servis;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Kartu statistik utama
        $jumlahPelanggan    = User::where('role', 'pelanggan')->count();
        $jumlahMontir       = User::where('role', 'montir')->count();
        $servisHariIni      = Servis::whereDate('tanggal_servis', today())->count();
        $invoiceBelumLunas  = Invoice::where('status_pembayaran', 'Belum Lunas')->count();

        // Statistik tambahan (bulan ini)
        $servisSelesaiBulanIni = Servis::where('status_servis', 'selesai')
            ->whereMonth('tanggal_servis', now()->month)
            ->whereYear('tanggal_servis', now()->year)
            ->count();

        $omzetBulanIni = Invoice::where('status_pembayaran', 'Lunas')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_tagihan');

        // Data ringkas untuk tabel di bawah
        $servisTerbaru = Servis::with(['pelanggan', 'kendaraan'])
            ->latest()
            ->take(5)
            ->get();

        $invoiceTerbaru = Invoice::with(['pelanggan'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'total_pelanggan'        => $jumlahPelanggan,
            'total_montir'           => $jumlahMontir,
            'servis_hari_ini'        => $servisHariIni,
            'invoice_belum_lunas'    => $invoiceBelumLunas,
            'servis_selesai_bulan'   => $servisSelesaiBulanIni,
            'omzet_bulan_ini'        => $omzetBulanIni,
            'servis_terbaru'         => $servisTerbaru,
            'invoice_terbaru'        => $invoiceTerbaru,
        ]);
    }
}
