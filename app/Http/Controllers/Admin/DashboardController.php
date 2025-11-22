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
        // Menghitung jumlah data
        $jumlahPelanggan = User::where('role', 'pelanggan')->count();
        $jumlahMontir = User::where('role', 'montir')->count();
        $servisHariIni = Servis::whereDate('tanggal_servis', today())->count();
        $invoiceBelumLunas = Invoice::where('status_pembayaran', 'Belum Lunas')->count();

        // Kirim semua data ke view. Ingat, lokasinya sekarang di 'admin.dashboard'
        return view('admin.dashboard', [
            'total_pelanggan' => $jumlahPelanggan,
            'total_montir' => $jumlahMontir,
            'servis_hari_ini' => $servisHariIni,
            'invoice_belum_lunas' => $invoiceBelumLunas,
        ]);
    }
}