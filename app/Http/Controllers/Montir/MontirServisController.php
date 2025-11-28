<?php

namespace App\Http\Controllers\Montir;

use App\Http\Controllers\Controller;
use App\Models\Servis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MontirServisController extends Controller
{
    /** LIST SERVIS milik montir */
    public function index()
    {
        $servis_list = Servis::with(['pelanggan', 'kendaraan'])
            ->where('montir_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('montir.servis.index', compact('servis_list'));
    }

    /** DETAIL SERVIS */
    public function show(Servis $servis)
    {
        // Pastikan montir hanya bisa melihat servis miliknya
        if ($servis->montir_id !== Auth::id()) {
            abort(403, 'Servis ini bukan tugas Anda');
        }

        $servis->load(['pelanggan', 'kendaraan', 'detail_layanans', 'detail_spareparts']);

        return view('montir.servis.show', compact('servis'));
    }

    /** UPDATE STATUS */
    public function updateStatus(Servis $servis, Request $request)
{
    // ✅ pastikan ini servis milik montir yang login
    if ($servis->montir_id !== Auth::id()) {
        abort(403, 'Servis ini bukan tugas Anda.');
    }

    // catatan montir boleh kosong, tapi kalau diisi harus string
    $validated = $request->validate([
        'catatan_montir' => 'nullable|string',
    ]);

    // status sekarang
    $currentStatus = $servis->status_servis;
    $nextStatus    = $currentStatus;

    // urutan status yg boleh diubah montir:
    // menunggu -> dikerjakan -> selesai
    if ($currentStatus === 'menunggu') {
        $nextStatus = 'dikerjakan';
    } elseif ($currentStatus === 'dikerjakan') {
        $nextStatus = 'selesai';
    } else {
        // kalau sudah selesai/dibayar/dibatalkan, montir tidak boleh ubah lagi
        return back()->with('error', 'Status ini tidak bisa diubah lagi oleh montir.');
    }

    // update servis
    $servis->update([
        'status_servis'   => $nextStatus,
        'catatan_montir'  => $validated['catatan_montir'] ?? $servis->catatan_montir,
    ]);

    return back()->with(
        'success',
        'Status servis diupdate menjadi "' . ucfirst($nextStatus) . '".'
    );
}
}
