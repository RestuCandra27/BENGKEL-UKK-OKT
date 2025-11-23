<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servis;
use App\Models\User;
use App\Models\Kendaraan;
use Illuminate\Http\Request;

class ServisController extends Controller
{
    public function index()
    {
        // Ambil data servis terbaru, lengkap dengan relasinya
        $servis_list = Servis::with(['pelanggan', 'kendaraan', 'montir'])
                             ->latest() // Urutkan dari yang paling baru
                             ->paginate(10);

        return view('admin.servis.index', compact('servis_list'));
    }

    public function create()
    {
        // 1. Ambil Pelanggan (untuk dropdown)
        $pelanggans = User::where('role', 'pelanggan')->orderBy('nama', 'asc')->get();
        
        // 2. Ambil Montir (untuk dropdown)
        $montirs = User::where('role', 'montir')->orderBy('nama', 'asc')->get();
        
        // 3. Ambil Kendaraan (Nanti kita filter pakai JS, sekarang ambil semua dulu)
        $kendaraans = Kendaraan::with('user')->get();

        return view('admin.servis.create', compact('pelanggans', 'montirs', 'kendaraans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'montir_id' => 'required|exists:users,id',
            'keluhan' => 'required|string',
            'tanggal_servis' => 'required|date',
        ]);

        // Simpan data servis baru
        Servis::create([
            'user_id' => $request->user_id,
            'kendaraan_id' => $request->kendaraan_id,
            'montir_id' => $request->montir_id,
            'keluhan' => $request->keluhan,
            'tanggal_servis' => $request->tanggal_servis,
            'status_servis' => 'menunggu', // Status awal
        ]);

        return redirect()->route('admin.servis.index')
                         ->with('success', 'Pendaftaran servis berhasil.');
    }

    // ... fungsi edit, update, destroy kita buat nanti
}