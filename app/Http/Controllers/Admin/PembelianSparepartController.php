<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembelianSparepart;
use App\Models\Sparepart;
use Illuminate\Http\Request;

class PembelianSparepartController extends Controller
{
    /**
     * Menampilkan riwayat stok masuk.
     */
    public function index()
    {
        // Ambil data pembelian + relasi sparepart, urut dari terbaru
        $pembelians = PembelianSparepart::with('sparepart')
            ->orderBy('tanggal_masuk', 'desc')
            ->paginate(10);

        return view('admin.pembelian_spareparts.index', compact('pembelians'));
    }

    /**
     * Form input stok masuk.
     */
    public function create()
    {
        // Untuk dropdown sparepart
        $spareparts = Sparepart::orderBy('nama_sparepart', 'asc')->get();

        return view('admin.pembelian_spareparts.create', compact('spareparts'));
    }

    /**
     * Simpan stok masuk baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sparepart_id'  => ['required', 'exists:spareparts,id'],
            'tanggal_masuk' => ['required', 'date'],
            'jumlah_masuk'  => ['required', 'integer', 'min:1'],
            'harga_beli'    => ['required', 'numeric', 'min:0'],
            'harga_jual'    => ['required', 'numeric', 'min:0'],
        ]);

        // stok_tersisa awal = jumlah_masuk
        $validated['stok_tersisa'] = $validated['jumlah_masuk'];

        PembelianSparepart::create($validated);

        return redirect()->route('admin.pembelian-spareparts.index')
                         ->with('success', 'Stok masuk berhasil ditambahkan.');
    }

    /**
     * Form edit stok masuk.
     */
    public function edit(string $id)
    {
        $pembelian  = PembelianSparepart::findOrFail($id);
        $spareparts = Sparepart::orderBy('nama_sparepart', 'asc')->get();

        return view('admin.pembelian_spareparts.edit', compact('pembelian', 'spareparts'));
    }

    /**
     * Update data stok.
     *
     * Catatan: di sini kita tidak mengutak-atik stok_tersisa,
     * karena stok bisa sudah terpakai lewat modul Servis.
     */
    public function update(Request $request, string $id)
    {
        $pembelian = PembelianSparepart::findOrFail($id);

        $validated = $request->validate([
            'sparepart_id'  => ['required', 'exists:spareparts,id'],
            'tanggal_masuk' => ['required', 'date'],
            'jumlah_masuk'  => ['required', 'integer', 'min:1'],
            'harga_beli'    => ['required', 'numeric', 'min:0'],
            'harga_jual'    => ['required', 'numeric', 'min:0'],
        ]);

        // Hitung selisih jumlah (kalau kamu mau update stok_tersisa, bisa pakai ini)
        $selisih = $validated['jumlah_masuk'] - $pembelian->jumlah_masuk;

        // Update field utama
        $pembelian->update([
            'sparepart_id'  => $validated['sparepart_id'],
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'jumlah_masuk'  => $validated['jumlah_masuk'],
            'harga_beli'    => $validated['harga_beli'],
            'harga_jual'    => $validated['harga_jual'],
            // opsional: kalau mau stok_tersisa ikut disesuaikan
            // 'stok_tersisa'  => $pembelian->stok_tersisa + $selisih,
        ]);

        return redirect()->route('admin.pembelian-spareparts.index')
                         ->with('success', 'Data stok berhasil diperbarui.');
    }

    /**
     * Hapus data stok.
     *
     * Catatan: ini masih versi sederhana.
     * Kalau ingin lebih aman, kamu bisa cek dulu apakah stok_tersisa == jumlah_masuk
     * (artinya belum ada yang terpakai).
     */
    public function destroy(string $id)
    {
        $pembelian = PembelianSparepart::findOrFail($id);
        $pembelian->delete();

        return redirect()->route('admin.pembelian-spareparts.index')
                         ->with('success', 'Data stok berhasil dihapus.');
    }
}
