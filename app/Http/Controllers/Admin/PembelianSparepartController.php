<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembelianSparepart;
use Illuminate\Http\Request;

class PembelianSparepartController extends Controller
{
    /**
     * Menampilkan riwayat stok masuk.
     */
    public function index()
    {
        // Ambil data pembelian, urutkan dari yang terbaru
        // 'with('sparepart')' agar kita bisa menampilkan nama sparepart-nya (bukan cuma ID)
        $pembelians = PembelianSparepart::with('sparepart')
                        ->orderBy('tanggal_masuk', 'desc')
                        ->paginate(10);

        return view('admin.pembelian_spareparts.index', compact('pembelians'));
    }

   public function create()
    {
        // Ambil semua data sparepart untuk dipilih di dropdown
        $spareparts = \App\Models\Sparepart::orderBy('nama_sparepart', 'asc')->get();
        
        return view('admin.pembelian_spareparts.create', compact('spareparts'));
    }

    /**
     * Menyimpan data stok masuk.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id',
            'tanggal_masuk' => 'required|date',
            'jumlah_masuk' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        \App\Models\PembelianSparepart::create([
            'sparepart_id' => $request->sparepart_id,
            'tanggal_masuk' => $request->tanggal_masuk,
            'jumlah_masuk' => $request->jumlah_masuk,
            
            // PENTING UNTUK FIFO:
            // Saat barang baru masuk, stok tersisanya sama dengan jumlah masuk.
            'stok_tersisa' => $request->jumlah_masuk, 
            
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
        ]);

        return redirect()->route('admin.pembelian-spareparts.index')
                         ->with('success', 'Stok berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $pembelian = \App\Models\PembelianSparepart::findOrFail($id);
        $spareparts = \App\Models\Sparepart::orderBy('nama_sparepart', 'asc')->get();

        return view('admin.pembelian_spareparts.edit', compact('pembelian', 'spareparts'));
    }

    /**
     * Update data stok.
     */
    public function update(Request $request, string $id)
    {
        $pembelian = \App\Models\PembelianSparepart::findOrFail($id);

        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id',
            'tanggal_masuk' => 'required|date',
            'jumlah_masuk' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        // PENTING: Jika jumlah diubah, kita reset stok_tersisa agar sama dengan jumlah baru.
        // (Asumsi: Data ini diedit karena salah input di awal dan belum ada yang terjual).
        $pembelian->update([
            'sparepart_id' => $request->sparepart_id,
            'tanggal_masuk' => $request->tanggal_masuk,
            'jumlah_masuk' => $request->jumlah_masuk,
            'stok_tersisa' => $request->jumlah_masuk, // Reset stok
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
        ]);

        return redirect()->route('admin.pembelian-spareparts.index')
                         ->with('success', 'Data stok berhasil diperbarui.');
    }

    /**
     * Hapus data stok.
     */
    public function destroy(string $id)
    {
        $pembelian = \App\Models\PembelianSparepart::findOrFail($id);
        $pembelian->delete();

        return redirect()->route('admin.pembelian-spareparts.index')
                         ->with('success', 'Data stok berhasil dihapus.');
    }
}