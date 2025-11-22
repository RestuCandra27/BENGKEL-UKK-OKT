<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketServis;
use App\Models\Layanan; // Kita butuh ini untuk dropdown/checkbox
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaketServisController extends Controller
{
    /**
     * Menampilkan daftar paket.
     */
    public function index()
    {
        // with('layanans') agar kita bisa lihat isi paketnya apa saja
        $pakets = PaketServis::with('layanans')->orderBy('nama_paket', 'asc')->paginate(10);
        return view('admin.paket_servis.index', compact('pakets'));
    }

    /**
     * Menampilkan form tambah.
     */
    public function create()
    {
        // Ambil semua layanan agar admin bisa memilih isian paket
        $layanans = Layanan::orderBy('nama_layanan', 'asc')->get();
        
        return view('admin.paket_servis.create', compact('layanans'));
    }

    /**
     * Menyimpan data paket baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_paket' => 'required|string|max:20|unique:paket_servis,kode_paket',
            'nama_paket' => 'required|string|max:100',
            'harga_paket' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            // Validasi array layanan (wajib pilih minimal 1)
            'layanan_ids' => 'required|array|min:1',
            'layanan_ids.*' => 'exists:layanans,id',
        ]);

        // 1. Simpan Data Paket Utama
        $paket = PaketServis::create([
            'kode_paket' => $request->kode_paket,
            'nama_paket' => $request->nama_paket,
            'harga_paket' => $request->harga_paket,
            'deskripsi' => $request->deskripsi,
        ]);

        // 2. Simpan Detail Isi Paket (Hubungkan ke Layanan)
        // Fungsi 'attach' akan mengisi tabel pivot 'detail_paket_layanan'
        $paket->layanans()->attach($request->layanan_ids);

        return redirect()->route('admin.paket-servis.index')
                         ->with('success', 'Paket servis berhasil dibuat.');
    }

    /**
     * Menampilkan form edit.
     */
    public function edit($id)
    {
        $paket = PaketServis::with('layanans')->findOrFail($id);
        $layanans = Layanan::orderBy('nama_layanan', 'asc')->get();
        
        // Ambil ID layanan yang sudah terhubung untuk auto-check checkbox
        $selectedLayananIDs = $paket->layanans->pluck('id')->toArray();

        return view('admin.paket_servis.edit', compact('paket', 'layanans', 'selectedLayananIDs'));
    }

    /**
     * Update data paket.
     */
    public function update(Request $request, $id)
    {
        $paket = PaketServis::findOrFail($id);

        $request->validate([
            'kode_paket' => ['required', 'max:20', Rule::unique('paket_servis', 'kode_paket')->ignore($paket->id)],
            'nama_paket' => 'required|string|max:100',
            'harga_paket' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'layanan_ids' => 'required|array|min:1',
            'layanan_ids.*' => 'exists:layanans,id',
        ]);

        // 1. Update Data Utama
        $paket->update([
            'kode_paket' => $request->kode_paket,
            'nama_paket' => $request->nama_paket,
            'harga_paket' => $request->harga_paket,
            'deskripsi' => $request->deskripsi,
        ]);

        // 2. Update Hubungan (Sync)
        // 'sync' sangat pintar: dia akan menghapus hubungan lama dan memasukkan yang baru
        $paket->layanans()->sync($request->layanan_ids);

        return redirect()->route('admin.paket-servis.index')
                         ->with('success', 'Paket servis berhasil diperbarui.');
    }

    /**
     * Hapus paket.
     */
    public function destroy($id)
    {
        $paket = PaketServis::findOrFail($id);
        
        // Hubungan di tabel pivot akan otomatis terhapus karena kita pakai onDelete('cascade') di migrasi
        $paket->delete();

        return redirect()->route('admin.paket-servis.index')
                         ->with('success', 'Paket servis berhasil dihapus.');
    }
}