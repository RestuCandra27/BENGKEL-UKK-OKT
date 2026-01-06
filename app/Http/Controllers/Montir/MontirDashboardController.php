<?php

namespace App\Http\Controllers\Montir;

use App\Http\Controllers\Controller;
use App\Models\Servis;
use Illuminate\Support\Facades\Auth;

class MontirDashboardController extends Controller
{
    public function index()
    {
        $montirId = Auth::id();

        // Statistik servis montir
        $totalServis = Servis::where('montir_id', $montirId)->count();

        $servisMenunggu = Servis::where('montir_id', $montirId)
            ->where('status_servis', 'menunggu')
            ->count();

        $servisDikerjakan = Servis::where('montir_id', $montirId)
            ->where('status_servis', 'dikerjakan')
            ->count();

        $servisSelesai = Servis::where('montir_id', $montirId)
            ->whereIn('status_servis', ['selesai', 'dibayar'])
            ->count();

        // Servis yang sedang aktif
        $servisAktif = Servis::with(['pelanggan', 'kendaraan'])
            ->where('montir_id', $montirId)
            ->whereIn('status_servis', ['menunggu', 'dikerjakan'])
            ->orderBy('tanggal_servis', 'asc')
            ->get();

        // Riwayat servis terakhir
        $servisTerakhir = Servis::with(['pelanggan', 'kendaraan'])
            ->where('montir_id', $montirId)
            ->whereIn('status_servis', ['selesai', 'dibayar'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('montir.dashboard', [
            'total_servis'      => $totalServis,
            'servis_menunggu'  => $servisMenunggu,
            'servis_dikerjakan'=> $servisDikerjakan,
            'servis_selesai'   => $servisSelesai,
            'servis_aktif'    => $servisAktif,
            'servis_terakhir' => $servisTerakhir,
        ]);
    }
}
