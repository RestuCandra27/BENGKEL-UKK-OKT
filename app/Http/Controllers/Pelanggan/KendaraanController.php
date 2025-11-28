<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KendaraanController extends Controller
{
    /**
     * Daftar semua kendaraan milik pelanggan yang login.
     */
    public function index()
    {
        $userId = Auth::id();

        $kendaraans = Kendaraan::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pelanggan.kendaraans.index', compact('kendaraans'));
    }

    /**
     * Form tambah kendaraan baru.
     */
    public function create()
    {
        return view('pelanggan.kendaraans.create');
    }

    /**
     * Simpan kendaraan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'merek'        => 'nullable|string|max:50',
            'model'        => 'nullable|string|max:50',
            'no_polisi'    => 'required|string|max:20|unique:kendaraans,no_polisi',
            'tahun'        => 'nullable|digits:4',
            'warna'        => 'nullable|string|max:30',
            'nomor_rangka' => 'required|string|max:50|unique:kendaraans,nomor_rangka',
            'nomor_mesin'  => 'required|string|max:50|unique:kendaraans,nomor_mesin',
        ]);


        Kendaraan::create([
            'user_id'      => Auth::id(),
            'merek'        => $request->merek,
            'model'        => $request->model,
            'no_polisi'    => $request->no_polisi,
            'tahun'        => $request->tahun,
            'warna'        => $request->warna,
            'nomor_rangka' => $request->nomor_rangka,
            'nomor_mesin'  => $request->nomor_mesin,
        ]);

        return redirect()
            ->route('pelanggan.kendaraans.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    /**
     * Form edit kendaraan.
     */
    public function edit($id)
    {
        $userId = Auth::id();

        $kendaraan = Kendaraan::where('user_id', $userId)->findOrFail($id);

        return view('pelanggan.kendaraans.edit', compact('kendaraan'));
    }

    /**
     * Update kendaraan.
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $kendaraan = Kendaraan::where('user_id', $userId)->findOrFail($id);

        $request->validate([
            'merek'        => 'nullable|string|max:50',
            'model'        => 'nullable|string|max:50',
            'no_polisi'    => 'required|string|max:20|unique:kendaraans,no_polisi,' . $kendaraan->id,
            'tahun'        => 'nullable|digits:4',
            'warna'        => 'nullable|string|max:30',
            'nomor_rangka' => 'required|string|max:50|unique:kendaraans,nomor_rangka,' . $kendaraan->id,
            'nomor_mesin'  => 'required|string|max:50|unique:kendaraans,nomor_mesin,' . $kendaraan->id,
        ]);


        $kendaraan->update([
            'merek'        => $request->merek,
            'model'        => $request->model,
            'no_polisi'    => $request->no_polisi,
            'tahun'        => $request->tahun,
            'warna'        => $request->warna,
            'nomor_rangka' => $request->nomor_rangka,
            'nomor_mesin'  => $request->nomor_mesin,
        ]);

        return redirect()
            ->route('pelanggan.kendaraans.index')
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }


    /**
     * Hapus kendaraan milik pelanggan.
     * (opsional: bisa dicek dulu apakah kendaraan masih punya servis aktif)
     */
    public function destroy($id)
    {
        $userId = Auth::id();

        $kendaraan = Kendaraan::where('user_id', $userId)->findOrFail($id);

        // TODO (opsional): cek apakah kendaraan punya servis yang statusnya belum "dibatalkan"

        $kendaraan->delete();

        return redirect()
            ->route('pelanggan.kendaraans.index')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }
}
