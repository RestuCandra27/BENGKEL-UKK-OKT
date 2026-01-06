<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use App\Models\Servis;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReservasiController extends Controller
{
    /**
     * List semua reservasi (semua pelanggan).
     */
    public function index()
    {
        $reservasis = Reservasi::with(['user', 'kendaraan'])
            ->orderBy('tanggal_booking', 'desc')
            ->orderBy('jam_booking', 'desc')
            ->paginate(15);

        return view('admin.reservasis.index', compact('reservasis'));
    }

    /**
     * Detail satu reservasi.
     */
    public function show(Reservasi $reservasi)
    {
        $montirs = User::where('role', 'montir')
            ->orderBy('nama', 'asc')
            ->get();

        return view('admin.reservasis.show', compact('reservasi', 'montirs'));
    }

    /**
     * Setujui reservasi.
     */
    public function approve(Request $request, Reservasi $reservasi)
    {
        $request->validate([
            'montir_id' => ['required', 'exists:users,id'],
        ]);

        DB::transaction(function () use ($request, $reservasi) {

            // 1. Update status reservasi
            $reservasi->update([
                'status' => 'disetujui',
            ]);

            // 2. Buat servis untuk montir
            Servis::create([
                'reservasi_id'   => $reservasi->id,      // hubungkan ke reservasi
                'user_id'        => $reservasi->user_id,
                'kendaraan_id'   => $reservasi->kendaraan_id ?? null,
                'montir_id'      => $request->montir_id,
                'keluhan'        => $reservasi->keluhan,
                'tanggal_servis' => $reservasi->tanggal ?? now()->toDateString(),
                'status_servis'  => 'menunggu',
                'total_biaya'    => 0,
            ]);
        });

        return redirect()
            ->route('admin.reservasis.index')
            ->with('success', 'Reservasi disetujui dan servis sudah dibuat untuk montir.');
    }

    /**
     * Tolak reservasi.
     */
    public function reject(Request $request, Reservasi $reservasi)
    {
        // Bisa kamu tambah validasi alasan penolakan kalau nanti ada kolomnya
        if ($reservasi->status === 'dibatalkan') {
            return back()->with('error', 'Reservasi yang sudah dibatalkan tidak bisa ditolak.');
        }

        $reservasi->update([
            'status' => 'ditolak',
        ]);

        return redirect()
            ->route('admin.reservasis.show', $reservasi->id)
            ->with('success', 'Reservasi ditandai sebagai ditolak.');
    }
}
