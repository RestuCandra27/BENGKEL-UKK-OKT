<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SparepartController extends Controller
{
    /**
     * Menampilkan daftar sparepart.
     */
    public function index()
    {
        $spareparts = Sparepart::orderBy('nama_sparepart', 'asc')->paginate(10);
        return view('admin.spareparts.index', compact('spareparts'));
    }

    /**
     * Menampilkan form tambah.
     */
    public function create()
    {
        return view('admin.spareparts.create');
    }

    /**
     * Menyimpan data baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_sku' => 'nullable|string|max:50|unique:spareparts,kode_sku',
            'nama_sparepart' => 'required|string|max:100',
            'merek' => 'nullable|string|max:50',
            'kategori' => 'required|string|max:50', // Wajib diisi biar rapi
        ]);

        Sparepart::create([
            'kode_sku' => $request->kode_sku,
            'nama_sparepart' => $request->nama_sparepart,
            'merek' => $request->merek,
            'kategori' => $request->kategori,
        ]);

        return redirect()->route('admin.spareparts.index')
            ->with('success', 'Sparepart baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit.
     */
    public function edit(Sparepart $sparepart)
    {
        return view('admin.spareparts.edit', compact('sparepart'));
    }

    /**
     * Update data.
     */
    public function update(Request $request, Sparepart $sparepart)
    {
        $request->validate([
            // Validasi unik, tapi abaikan ID sparepart ini sendiri
            'kode_sku' => [
                'nullable',
                'string',
                'max:50',
                'merek' => $request->merek,
                'kategori' => $request->kategori,
                Rule::unique('spareparts', 'kode_sku')->ignore($sparepart->id)
            ],
            'nama_sparepart' => 'required|string|max:100',
        ]);

        $sparepart->update([
            'kode_sku' => $request->kode_sku,
            'nama_sparepart' => $request->nama_sparepart,
        ]);

        return redirect()->route('admin.spareparts.index')
            ->with('success', 'Data sparepart berhasil diperbarui.');
    }

    /**
     * Hapus data.
     */
    public function destroy(Sparepart $sparepart)
    {
        $sparepart->delete();
        return redirect()->route('admin.spareparts.index')
            ->with('success', 'Data sparepart berhasil dihapus.');
    }
}
