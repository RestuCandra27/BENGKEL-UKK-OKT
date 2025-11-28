<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Servis;
use Illuminate\Support\Facades\Auth;

class ServisController extends Controller
{
    /**
     * Menampilkan daftar servis milik pelanggan yang login.
     */
    public function index()
    {
        $user = Auth::user();

        $servis_list = Servis::with(['kendaraan'])
            ->where('user_id', $user->id)
            ->orderBy('tanggal_servis', 'desc')
            ->paginate(10);

        return view('pelanggan.servis.index', compact('servis_list'));
    }

    /**
     * Menampilkan detail 1 servis (lengkap: jasa & sparepart)
     * Hanya bisa diakses oleh pemilik (user_id = user login).
     */
    public function show($id)
    {
        $userId = Auth::id();

        $servis = Servis::with([
                'pelanggan',
                'kendaraan',
                'montir',
                'detail_layanans',
                'detail_spareparts',
            ])
            ->where('user_id', $userId)
            ->findOrFail($id);

        // Hitung total jasa
        $totalJasa = $servis->detail_layanans->sum('biaya_standar');

        // Hitung total sparepart
        $totalSparepart = 0;
        foreach ($servis->detail_spareparts as $part) {
            $totalSparepart += $part->pivot->jumlah_digunakan * $part->pivot->harga_saat_digunakan;
        }

        $totalBiaya = $totalJasa + $totalSparepart;

        return view('pelanggan.servis.show', compact('servis', 'totalJasa', 'totalSparepart', 'totalBiaya'));
    }
}
