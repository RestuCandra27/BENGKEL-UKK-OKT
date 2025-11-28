<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservasiController extends Controller
{
    /**
     * List semua reservasi milik pelanggan login.
     */
    public function index()
    {
        $reservasis = Reservasi::with('kendaraan')
            ->where('user_id', Auth::id())
            ->orderBy('tanggal_booking', 'desc')
            ->paginate(10);

        return view('pelanggan.reservasi.index', compact('reservasis'));
    }

    /**
     * Form buat reservasi baru.
     */
    public function create()
    {
        // hanya kendaraan milik user login
        $kendaraans = Kendaraan::where('user_id', Auth::id())
            ->orderBy('no_polisi')
            ->get();

        return view('pelanggan.reservasi.create', compact('kendaraans'));
    }

    /**
     * Simpan reservasi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kendaraan_id'    => 'required|exists:kendaraans,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_booking'     => 'required',
            'keluhan'         => 'required|string',
        ]);

        // Pastikan kendaraan memang milik user login
        $kendaraan = Kendaraan::where('id', $request->kendaraan_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Reservasi::create([
            'user_id'         => Auth::id(),
            'kendaraan_id'    => $kendaraan->id,
            'tanggal_booking' => $request->tanggal_booking,
            'jam_booking'     => $request->jam_booking,
            'keluhan'         => $request->keluhan,
            // ⬇ status awal SELALU pending (biar tidak NULL)
            'status'          => 'pending',
    ]);

        return redirect()
            ->route('pelanggan.reservasis.index')
            ->with('success', 'Reservasi berhasil dibuat. Silakan menunggu konfirmasi bengkel.');
    }

    /**
     * Detail satu reservasi.
     */
    public function show(Reservasi $reservasi)
    {
        // proteksi: reservasi harus milik user login
        if ($reservasi->user_id !== Auth::id()) {
            abort(404);
        }

        $reservasi->load('kendaraan');

        // hanya pending / disetujui yang boleh dibatalkan
        $bisaDibatalkan = in_array($reservasi->status, ['pending', 'disetujui']);

        return view('pelanggan.reservasi.show', compact('reservasi', 'bisaDibatalkan'));
    }

    /**
     * Pembatalan reservasi oleh pelanggan.
     */
    public function cancel(Reservasi $reservasi)
    {
        // proteksi: reservasi harus milik user login
        if ($reservasi->user_id !== Auth::id()) {
            abort(404);
        }

        // kalau bukan pending / disetujui → tidak bisa dibatalkan
        if (! in_array($reservasi->status, ['pending', 'disetujui'])) {
            return redirect()
                ->route('pelanggan.reservasis.show', $reservasi->id)
                ->with('error', 'Reservasi ini tidak bisa dibatalkan lagi.');
        }

        $reservasi->update([
            'status' => 'dibatalkan',
        ]);

        return redirect()
            ->route('pelanggan.reservasis.index')
            ->with('success', 'Reservasi berhasil dibatalkan.');
    }
}
