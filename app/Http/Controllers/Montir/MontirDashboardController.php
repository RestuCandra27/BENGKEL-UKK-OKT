<?php

namespace App\Http\Controllers\Montir;

use App\Http\Controllers\Controller;
use App\Models\Servis;
use Illuminate\Support\Facades\Auth; // ⬅️ tambah ini

class MontirDashboardController extends Controller
{
    public function index()
    {
        $montirId = Auth::id(); // ⬅️ pakai Auth::id()

        return view('montir.dashboard', [
            'total_pending'  => Servis::where('montir_id', $montirId)
                                    ->where('status_servis', 'menunggu')
                                    ->count(),
            'total_progress' => Servis::where('montir_id', $montirId)
                                    ->where('status_servis', 'dikerjakan')
                                    ->count(),
            'total_selesai'  => Servis::where('montir_id', $montirId)
                                    ->where('status_servis', 'selesai')
                                    ->count(),
        ]);
    }
}
