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
        // Ambil sparepart terbaru, pakai paginate karena di Blade pakai $spareparts->links()
        $spareparts = Sparepart::orderBy('nama_sparepart', 'asc')->paginate(10);

        return view('admin.spareparts.index', compact('spareparts'));
    }

    /**
     * Form tambah sparepart.
     */
    public function create()
    {
        return view('admin.spareparts.create');
    }

    /**
     * Simpan sparepart baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_sku'       => ['nullable', 'string', 'max:50', 'unique:spareparts,kode_sku'],
            'nama_sparepart' => ['required', 'string', 'max:100'],
            'merek'          => ['nullable', 'string', 'max:50'],
            'kategori'       => ['nullable', 'string', 'max:50'],
        ]);

        Sparepart::create($validated);

        return redirect()->route('admin.spareparts.index')
                         ->with('success', 'Sparepart baru berhasil ditambahkan.');
    }

    /**
     * Form edit sparepart.
     */
    public function edit(Sparepart $sparepart)
    {
        return view('admin.spareparts.edit', compact('sparepart'));
    }

    /**
     * Update data sparepart.
     */
    public function update(Request $request, Sparepart $sparepart)
    {
        $validated = $request->validate([
            'kode_sku'       => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('spareparts', 'kode_sku')->ignore($sparepart->id),
            ],
            'nama_sparepart' => ['required', 'string', 'max:100'],
            'merek'          => ['nullable', 'string', 'max:50'],
            'kategori'       => ['nullable', 'string', 'max:50'],
        ]);

        $sparepart->update($validated);

        return redirect()->route('admin.spareparts.index')
                         ->with('success', 'Data sparepart berhasil diperbarui.');
    }

    /**
     * Hapus data sparepart.
     */
    public function destroy(Sparepart $sparepart)
    {
        $sparepart->delete();

        return redirect()->route('admin.spareparts.index')
                         ->with('success', 'Data sparepart berhasil dihapus.');
    }
}
