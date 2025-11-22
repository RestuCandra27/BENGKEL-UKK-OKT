<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan; // Impor Model Layanan
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Impor untuk validasi

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
        $request->validate([
            'nama_layanan' => 'required|string|max:100|unique:layanans,nama_layanan',
            'biaya_standar' => 'required|numeric|min:0',
        ]);

        Layanan::create([
            'nama_layanan' => $request->nama_layanan,
            'biaya_standar' => $request->biaya_standar,
        ]);

        return redirect()->route('admin.layanans.index')
                         ->with('success', 'Layanan baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu layanan (tidak kita pakai saat ini).
     */
    public function show(string $id)
    {
        //
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
            'nama_layanan' => [
                'required',
                'string',
                'max:100','deskripsi' => 'nullable|string',
                Rule::unique('layanans', 'nama_layanan')->ignore($layanan->id)
                
            ],
            'biaya_standar' => 'required|numeric|min:0',
        ]);

        // 2. Jika validasi lolos, update data di database
        $layanan->update([
            'nama_layanan' => $request->nama_layanan,
            'biaya_standar' => $request->biaya_standar,
            'deskripsi' => $request->deskripsi,
        ]);

        // 3. Arahkan kembali ke halaman index dengan pesan sukses
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