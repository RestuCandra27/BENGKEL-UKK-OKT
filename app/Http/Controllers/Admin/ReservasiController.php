<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\Request;

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
        $reservasi->load(['user', 'kendaraan']);

        return view('admin.reservasis.show', compact('reservasi'));
    }

    /**
     * Setujui reservasi.
     */
    public function approve(Reservasi $reservasi)
    {
        // Kalau sudah dibatalkan, jangan bisa disetujui
        if ($reservasi->status === 'dibatalkan') {
            return back()->with('error', 'Reservasi yang sudah dibatalkan tidak bisa disetujui.');
        }

        $reservasi->update([
            'status' => 'disetujui',
        ]);

        return redirect()
            ->route('admin.reservasis.show', $reservasi->id)
            ->with('success', 'Reservasi berhasil disetujui.');
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
