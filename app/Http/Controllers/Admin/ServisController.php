<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servis;
use App\Models\User;
use App\Models\Kendaraan;
use App\Models\Layanan;
use App\Models\PaketServis;
use App\Models\Sparepart;
use App\Models\PembelianSparepart;
use Illuminate\Http\Request;

class ServisController extends Controller
{
    /** ============================
     *  LIST SERVIS
     *  ============================ */
    public function index()
    {
        // Ambil data servis terbaru beserta relasinya (pelanggan, kendaraan, montir)
        $servis_list = Servis::with(['pelanggan', 'kendaraan', 'montir'])
            ->latest()
            ->paginate(10);

        return view('admin.servis.index', compact('servis_list'));
    }

    /** ============================
     *  FORM TAMBAH SERVIS
     *  ============================ */
    public function create()
    {
        // Pelanggan
        $pelanggans = User::where('role', 'pelanggan')
            ->orderBy('nama', 'asc')
            ->get();

        // Montir
        $montirs = User::where('role', 'montir')
            ->orderBy('nama', 'asc')
            ->get();

        // Kendaraan
        $kendaraans = Kendaraan::with('user')->get();

        return view('admin.servis.create', compact('pelanggans', 'montirs', 'kendaraans'));
    }

    /** ============================
     *  SIMPAN SERVIS BARU
     *  ============================ */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'kendaraan_id'   => 'required|exists:kendaraans,id',
            'montir_id'      => 'required|exists:users,id',
            'keluhan'        => 'required|string',
            'tanggal_servis' => 'required|date',
        ]);

        Servis::create([
            'user_id'        => $request->user_id,
            'kendaraan_id'   => $request->kendaraan_id,
            'montir_id'      => $request->montir_id,
            'keluhan'        => $request->keluhan,
            'tanggal_servis' => $request->tanggal_servis,
            'status_servis'  => 'menunggu',
            'total_biaya'    => 0,
        ]);

        return redirect()->route('admin.servis.index')
            ->with('success', 'Pendaftaran servis berhasil.');
    }

    /** ============================
     *  FORM EDIT / PROSES SERVIS
     *  ============================ */
    public function edit($id)
    {
        $servis = Servis::with([
            'pelanggan',
            'kendaraan',
            'montir',
            'detail_layanans',
            'detail_spareparts',
        ])->findOrFail($id);

        $all_layanans     = Layanan::orderBy('nama_layanan', 'asc')->get();
        $all_spareparts   = Sparepart::orderBy('nama_sparepart', 'asc')->get();
        $all_paket_servis = PaketServis::with('layanans')->get();

        return view('admin.servis.edit', compact(
            'servis',
            'all_layanans',
            'all_spareparts',
            'all_paket_servis'
        ));
    }

    /** ============================
     *  UPDATE STATUS SERVIS (menunggu → dikerjakan → selesai → dibayar/dibatalkan)
     *  ============================ */
    public function update(Request $request, $id)
    {
        $servis = Servis::findOrFail($id);

        $request->validate([
            'status_servis' => 'required|in:menunggu,dikerjakan,selesai,dibayar,dibatalkan',
            'keluhan'       => 'required|string',
        ]);

        $servis->update([
            'status_servis' => $request->status_servis,
            'keluhan'       => $request->keluhan,
        ]);

        return redirect()->route('admin.servis.index')
            ->with('success', 'Status servis berhasil diperbarui.');
    }

    /** ============================
     *  HAPUS SERVIS + KEMBALIKAN STOK
     *  ============================ */
    public function destroy($id)
    {
        $servis = Servis::with('detail_spareparts')->findOrFail($id);

        // Kembalikan stok sparepart
        foreach ($servis->detail_spareparts as $part) {
            if ($part->pivot->pembelian_sparepart_id) {
                $batch = PembelianSparepart::find($part->pivot->pembelian_sparepart_id);
                if ($batch) {
                    $batch->increment('stok_tersisa', $part->pivot->jumlah_digunakan);
                }
            }
        }

        // Hapus relasi pivot
        $servis->detail_layanans()->detach();
        $servis->detail_spareparts()->detach();

        // Hapus servis
        $servis->delete();

        return redirect()->route('admin.servis.index')
            ->with('success', 'Data servis berhasil dihapus.');
    }

    /** ============================
     *  TAMBAH LAYANAN ATAU PAKET SERVIS
     *  ============================ */
    public function storeLayanan(Request $request, Servis $servis)
    {
        // Tambah LAYANAN biasa
        if ($request->filled('layanan_id')) {
            $request->validate([
                'layanan_id' => 'required|exists:layanans,id',
            ]);

            // Cek ke database, hindari duplikat
            $sudahAda = $servis->detail_layanans()
                ->where('layanans.id', $request->layanan_id)
                ->exists();

            if (! $sudahAda) {
                $servis->detail_layanans()->attach($request->layanan_id);
            }
        }

        // Tambah PAKET SERVIS
        if ($request->filled('paket_servis_id')) {
            $request->validate([
                'paket_servis_id' => 'required|exists:paket_servis,id',
            ]);

            $paket = PaketServis::with('layanans')->findOrFail($request->paket_servis_id);

            foreach ($paket->layanans as $layan) {
                $sudahAda = $servis->detail_layanans()
                    ->where('layanans.id', $layan->id)
                    ->exists();

                if (! $sudahAda) {
                    $servis->detail_layanans()->attach($layan->id);
                }
            }
        }

        // Hitung ulang total biaya setelah ada perubahan
        $this->hitungTotalBiaya($servis);

        return back()->with('success', 'Layanan atau paket servis berhasil ditambahkan.');
    }

    /** ============================
     *  HAPUS LAYANAN
     *  ============================ */
    public function destroyLayanan(Servis $servis, $layananId)
    {
        $servis->detail_layanans()->detach($layananId);

        $this->hitungTotalBiaya($servis);

        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    /** ============================
     *  TAMBAH SPAREPART
     *  ============================ */
    public function storeSparepart(Request $request, Servis $servis)
    {
        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id',
            'jumlah'       => 'required|integer|min:1',
        ]);

        // FIFO Stock: ambil batch pembelian terlama yang masih ada stok
        $batch = PembelianSparepart::where('sparepart_id', $request->sparepart_id)
            ->where('stok_tersisa', '>', 0)
            ->orderBy('tanggal_masuk', 'asc')
            ->first();

        if (! $batch) {
            return back()->with('error', 'Stok sparepart ini habis!');
        }

        if ($batch->stok_tersisa < $request->jumlah) {
            return back()->with(
                'error',
                'Stok pada batch ini tidak cukup. Sisa: ' . $batch->stok_tersisa
            );
        }

        // Kurangi stok batch
        $batch->decrement('stok_tersisa', $request->jumlah);

        // Tambahkan ke pivot
        $servis->detail_spareparts()->attach($request->sparepart_id, [
            'jumlah_digunakan'       => $request->jumlah,
            'harga_saat_digunakan'   => $batch->harga_jual,
            'pembelian_sparepart_id' => $batch->id,
        ]);

        $this->hitungTotalBiaya($servis);

        return back()->with('success', 'Sparepart berhasil ditambahkan.');
    }

    /** ============================
     *  HAPUS SPAREPART
     *  ============================ */
    public function destroySparepart(Servis $servis, $sparepartId)
    {
        $data = $servis->detail_spareparts()
            ->where('sparepart_id', $sparepartId)
            ->first();

        if (! $data) {
            return back()->with('error', 'Data sparepart tidak ditemukan.');
        }

        $pivot = $data->pivot;

        // Kembalikan stok
        if ($pivot->pembelian_sparepart_id) {
            $batch = PembelianSparepart::find($pivot->pembelian_sparepart_id);
            if ($batch) {
                $batch->increment('stok_tersisa', $pivot->jumlah_digunakan);
            }
        }

        // Hapus pivot
        $servis->detail_spareparts()->detach($sparepartId);

        $this->hitungTotalBiaya($servis);

        return back()->with('success', 'Sparepart dihapus & stok dikembalikan.');
    }

    /** ============================
     *  HITUNG TOTAL BIAYA SERVIS
     *  ============================ */
    private function hitungTotalBiaya(Servis $servis)
    {
        // Paksa reload relasi dari database supaya data terbaru terbaca
        $servis->load(['detail_layanans', 'detail_spareparts']);

        // Total jasa = jumlah biaya_standar semua layanan yang ter-attach
        $totalJasa = $servis->detail_layanans->sum('biaya_standar');

        // Total sparepart = sum(jumlah_digunakan * harga_saat_digunakan)
        $totalPart = 0;
        foreach ($servis->detail_spareparts as $part) {
            $totalPart += $part->pivot->jumlah_digunakan * $part->pivot->harga_saat_digunakan;
        }

        $servis->total_biaya = $totalJasa + $totalPart;
        $servis->save();
    }
}
