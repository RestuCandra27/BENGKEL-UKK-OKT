<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\User; // Kita butuh ini untuk mengambil data pelanggan
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KendaraanController extends Controller
{
    /**
     * Menampilkan daftar kendaraan.
     */
    public function index()
    {
        // Ambil kendaraan beserta data pemiliknya (user), urutkan terbaru
        $kendaraans = Kendaraan::with('user')->latest()->paginate(10);
        return view('admin.kendaraans.index', compact('kendaraans'));
    }

    /**
     * Menampilkan form tambah kendaraan.
     */
    public function create()
    {
        // Ambil semua user yang rolenya 'pelanggan' untuk dropdown
        $pelanggans = User::where('role', 'pelanggan')->orderBy('nama', 'asc')->get();
        
        return view('admin.kendaraans.create', compact('pelanggans'));
    }

    /**
     * Menyimpan data kendaraan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', // Harus ada di tabel users
            'no_polisi' => 'required|string|max:20|unique:kendaraans,no_polisi',
            'merek' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'tahun' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'warna' => 'nullable|string|max:30',
            'nomor_rangka' => 'nullable|string|max:50|unique:kendaraans,nomor_rangka',
            'nomor_mesin' => 'nullable|string|max:50|unique:kendaraans,nomor_mesin',
        ]);

        Kendaraan::create($request->all());

        return redirect()->route('admin.kendaraans.index')
                         ->with('success', 'Kendaraan baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit.
     */
    public function edit(Kendaraan $kendaraan)
    {
        $pelanggans = User::where('role', 'pelanggan')->orderBy('nama', 'asc')->get();
        return view('admin.kendaraans.edit', compact('kendaraan', 'pelanggans'));
    }

    /**
     * Update data kendaraan.
     */
    public function update(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            // Validasi unik, tapi abaikan ID kendaraan ini sendiri
            'no_polisi' => ['required', 'max:20', Rule::unique('kendaraans', 'no_polisi')->ignore($kendaraan->id)],
            'merek' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'tahun' => 'nullable|integer',
            'warna' => 'nullable|string|max:30',
            'nomor_rangka' => ['nullable', 'max:50', Rule::unique('kendaraans', 'nomor_rangka')->ignore($kendaraan->id)],
            'nomor_mesin' => ['nullable', 'max:50', Rule::unique('kendaraans', 'nomor_mesin')->ignore($kendaraan->id)],
        ]);

        $kendaraan->update($request->all());

        return redirect()->route('admin.kendaraans.index')
                         ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    /**
     * Hapus kendaraan.
     */
    public function destroy(Kendaraan $kendaraan)
    {
        $kendaraan->delete();
        return redirect()->route('admin.kendaraans.index')->with('success', 'Data kendaraan berhasil dihapus.');
    }
}