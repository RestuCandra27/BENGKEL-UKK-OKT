<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembelianSparepart;
use App\Models\Sparepart;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianSparepartController extends Controller
{
    /**
     * Menampilkan riwayat stok masuk.
     */
    public function index()
    {
        // Ambil data stok masuk + relasi sparepart, urut dari terbaru
        $pembelians = PembelianSparepart::with('sparepart')
            ->orderBy('tanggal_masuk', 'desc')
            ->orderBy('created_at', 'desc')
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
     * Simpan stok masuk baru + update stok sparepart.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sparepart_id'  => ['required', 'exists:spareparts,id'],
            'tanggal_masuk' => ['required', 'date'],
            'jumlah_masuk'  => ['required', 'integer', 'min:1'],
            'harga_beli'    => ['required', 'numeric', 'min:0'],
            'harga_jual'    => ['required', 'numeric', 'min:0'],
            'keterangan'    => ['nullable', 'string'],
        ]);

        // stok_tersisa awal = jumlah masuk
        $validated['stok_tersisa'] = $validated['jumlah_masuk'];

        DB::transaction(function () use ($validated) {

            // 1. Simpan riwayat stok masuk
            $pembelian = PembelianSparepart::create($validated);

            // 2. Update master sparepart (stok & harga_jual aktif)
            $sparepart = Sparepart::lockForUpdate()
                ->findOrFail($validated['sparepart_id']);

            // Tambah stok fisik
            $sparepart->increaseStock((int) $validated['jumlah_masuk']);

            // Set / update harga jual yang dipakai di modul servis
            $sparepart->harga_jual = (int) $validated['harga_jual'];
            $sparepart->save();

            // 3. Catat log aktivitas
            ActivityLogger::log(
                'Tambah stok masuk',
                $pembelian,
                [
                    'sparepart_id'   => $sparepart->id,
                    'nama_sparepart' => $sparepart->nama_sparepart,
                    'jumlah_masuk'   => $pembelian->jumlah_masuk,
                    'harga_beli'     => $pembelian->harga_beli,
                    'harga_jual'     => $sparepart->harga_jual,
                    'keterangan'     => $pembelian->keterangan ?? null,
                ]
            );
        });

        return redirect()
            ->route('admin.stok-masuk.index')
            ->with('success', 'Stok masuk berhasil ditambahkan & stok sparepart diperbarui.');
    }

    /**
     * Hapus data stok masuk + koreksi stok sparepart.
     *
     * Catatan:
     * - Hanya aman kalau stok yang berasal dari entri ini belum sepenuhnya terpakai.
     * - Kalau stok sudah kurang dari jumlah_masuk, data tidak akan dihapus.
     */
    public function destroy(PembelianSparepart $pembelianSparepart)
    {
        $sparepart = $pembelianSparepart->sparepart;

        if (! $sparepart) {
            // Kalau entah kenapa sparepart sudah hilang, hapus saja lognya
            $pembelianSparepart->delete();

            // Log aktivitas sederhana
            ActivityLogger::log(
                'Hapus stok masuk (sparepart sudah tidak ada)',
                $pembelianSparepart,
                [
                    'pembelian_id' => $pembelianSparepart->id,
                ]
            );

            return redirect()
                ->route('admin.stok-masuk.index')
                ->with('success', 'Data stok masuk berhasil dihapus.');
        }

        // Cek dulu apakah stok cukup untuk dikurangi
        if ($sparepart->stok < $pembelianSparepart->jumlah_masuk) {
            return redirect()
                ->route('admin.stok-masuk.index')
                ->with('error', 'Stok sparepart sudah terpakai, data stok masuk tidak dapat dihapus.');
        }

        DB::transaction(function () use ($sparepart, $pembelianSparepart) {
            $jumlah = $pembelianSparepart->jumlah_masuk;

            // Kurangi stok sparepart
            $sparepart->decrement('stok', $jumlah);

            // Hapus log stok masuk
            $pembelianSparepart->delete();

            // Log aktivitas
            ActivityLogger::log(
                'Hapus stok masuk',
                $sparepart,
                [
                    'sparepart_id'   => $sparepart->id,
                    'nama_sparepart' => $sparepart->nama_sparepart,
                    'jumlah_dikurangi' => $jumlah,
                ]
            );
        });

        return redirect()
            ->route('admin.stok-masuk.index')
            ->with('success', 'Data stok masuk berhasil dihapus dan stok sparepart diperbarui.');
    }

    // ================================
    // OPSIONAL: Kalau nanti mau dipakai
    // ================================
    // public function edit(PembelianSparepart $pembelianSparepart) { ... }
    // public function update(Request $request, PembelianSparepart $pembelianSparepart) { ... }
}
