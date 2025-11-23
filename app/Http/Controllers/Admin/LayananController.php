<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LayananController extends Controller
{
    /**
     * Menampilkan daftar semua layanan.
     */
    public function index()
    {
        $layanans = Layanan::orderBy('nama_layanan', 'asc')->get();
        return view('admin.layanans.index', compact('layanans'));
    }

    /**
     * Menampilkan form untuk membuat layanan baru.
     */
    public function create()
    {
        return view('admin.layanans.create');
    }

    /**
     * Menyimpan layanan baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk
        $request->validate([
            'nama_layanan' => 'required|string|max:100|unique:layanans,nama_layanan',
            'biaya_standar' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string', // <-- Wajib ada agar deskripsi tervalidasi
        ]);

        // 2. Simpan data ke database
        Layanan::create([
            'nama_layanan' => $request->nama_layanan,
            'biaya_standar' => $request->biaya_standar,
            'deskripsi' => $request->deskripsi, // <-- Wajib ada agar tersimpan
        ]);

        return redirect()->route('admin.layanans.index')
                         ->with('success', 'Layanan baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit layanan.
     */
    public function edit(Layanan $layanan)
    {
        return view('admin.layanans.edit', compact('layanan'));
    }

    /**
     * Mengupdate data layanan di database.
     */
    public function update(Request $request, Layanan $layanan)
    {
        // 1. Validasi data yang masuk
        $request->validate([
            // Validasi nama (unik, tapi abaikan nama layanan ini sendiri)
            'nama_layanan' => [
                'required',
                'string',
                'max:100',
                Rule::unique('layanans', 'nama_layanan')->ignore($layanan->id)
            ],
            'biaya_standar' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string', // <-- Wajib ada
        ]);

        // 2. Update data di database
        $layanan->update([
            'nama_layanan' => $request->nama_layanan,
            'biaya_standar' => $request->biaya_standar,
            'deskripsi' => $request->deskripsi, // <-- Wajib ada
        ]);

        return redirect()->route('admin.layanans.index')
                         ->with('success', 'Data layanan berhasil diperbarui.');
    }

    /**
     * Menghapus data layanan dari database.
     */
    public function destroy(Layanan $layanan)
    {
        $layanan->delete();
        return redirect()->route('admin.layanans.index')->with('success', 'Data layanan berhasil dihapus.');
    }
}