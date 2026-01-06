<?php

namespace App\Http\Controllers\Montir;

use App\Http\Controllers\Controller;
use App\Models\Servis;
use App\Models\Layanan;
use App\Models\Sparepart;
use App\Models\RiwayatKondisi;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MontirServisController extends Controller
{
    /** LIST SERVIS milik montir */
    public function index()
    {
        $servis_list = Servis::with(['pelanggan', 'kendaraan'])
            ->where('montir_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('montir.servis.index', compact('servis_list'));
    }

    /** DETAIL SERVIS */
    public function show(Servis $servis)
    {
        // Pastikan montir hanya bisa melihat servis miliknya
        if ($servis->montir_id !== Auth::id()) {
            abort(403, 'Servis ini bukan tugas Anda');
        }

        // load relasi
        $servis->load(['pelanggan', 'kendaraan', 'detail_layanans', 'spareparts', 'riwayat_kondisi']);

        // data untuk dropdown
        $all_layanans   = Layanan::orderBy('nama_layanan', 'asc')->get();
        $all_spareparts = Sparepart::orderBy('nama_sparepart', 'asc')->get();

        return view('montir.servis.show', compact(
            'servis',
            'all_layanans',
            'all_spareparts'
        ));
    }

    /** UPDATE STATUS SERVIS OLEH MONTIR */
    public function updateStatus(Servis $servis, Request $request)
    {
        // pastikan ini servis milik montir yang login
        if ($servis->montir_id !== Auth::id()) {
            abort(403, 'Servis ini bukan tugas Anda.');
        }

        // catatan montir boleh kosong, tapi kalau diisi harus string
        $validated = $request->validate([
            'catatan_montir' => 'nullable|string',
        ]);

        // status sekarang
        $currentStatus = $servis->status_servis;
        $nextStatus    = $currentStatus;

        // urutan status yg boleh diubah montir: menunggu -> dikerjakan -> selesai
        if ($currentStatus === 'menunggu') {
            $nextStatus = 'dikerjakan';
        } elseif ($currentStatus === 'dikerjakan') {
            $nextStatus = 'selesai';
        } else {
            return back()->with('error', 'Status ini tidak bisa diubah lagi oleh montir.');
        }

        $oldCatatan = $servis->catatan_montir;

        // update servis
        $servis->update([
            'status_servis'   => $nextStatus,
            'catatan_montir'  => $validated['catatan_montir'] ?? $servis->catatan_montir,
        ]);

        // LOG aktivitas montir
        ActivityLogger::log(
            'Montir update status servis',
            $servis,
            [
                'montir_id'      => $servis->montir_id,
                'status_lama'    => $currentStatus,
                'status_baru'    => $nextStatus,
                'catatan_lama'   => $oldCatatan,
                'catatan_baru'   => $servis->catatan_montir,
            ]
        );

        return back()->with(
            'success',
            'Status servis diupdate menjadi "' . ucfirst($nextStatus) . '".'
        );
    }

    /** UPDATE / SIMPAN RIWAYAT KONDISI SERVIS */
    public function updateRiwayat(Servis $servis, Request $request)
    {
        // Pastikan ini servis milik montir yang login
        if ($servis->montir_id !== Auth::id()) {
            abort(403, 'Servis ini bukan tugas Anda.');
        }

        $data = $request->validate([
            'catatan_saat_masuk'      => ['nullable', 'string'],
            'catatan_setelah_selesai' => ['nullable', 'string'],
            'rekomendasi_montir'      => ['nullable', 'string'],
        ]);

        // kalau sudah ada riwayat -> ambil, kalau belum -> buat
        $riwayat = $servis->riwayat_kondisi ?: new RiwayatKondisi(['servis_id' => $servis->id]);

        $riwayat->fill($data);
        $riwayat->servis_id = $servis->id;
        $riwayat->save();

        // LOG aktivitas montir
        ActivityLogger::log(
            'Montir mengisi/ubah riwayat kondisi servis',
            $servis,
            [
                'montir_id'             => $servis->montir_id,
                'riwayat_id'            => $riwayat->id,
                'catatan_saat_masuk'    => $riwayat->catatan_saat_masuk,
                'catatan_setelah_selesai'=> $riwayat->catatan_setelah_selesai,
                'rekomendasi_montir'    => $riwayat->rekomendasi_montir,
            ]
        );

        return back()->with('success', 'Catatan servis berhasil disimpan.');
    }

    /** TAMBAH LAYANAN OLEH MONTIR */
    public function storeLayanan(Request $request, Servis $servis)
    {
        if (! in_array($servis->status_servis, ['menunggu', 'dikerjakan'])) {
            return back()->with('error', 'Layanan tidak bisa diubah pada status ini.');
        }

        if ($servis->montir_id !== Auth::id()) {
            abort(403, 'Servis ini bukan tugas Anda.');
        }

        $data = $request->validate([
            'layanan_id' => ['required', 'exists:layanans,id'],
        ]);

        // Cegah duplikat
        $sudahAda = $servis->detail_layanans()
            ->where('layanans.id', $data['layanan_id'])
            ->exists();

        if (! $sudahAda) {
            $servis->detail_layanans()->attach($data['layanan_id']);

            $layanan = Layanan::find($data['layanan_id']);

            ActivityLogger::log(
                'Montir menambahkan layanan ke servis',
                $servis,
                [
                    'montir_id'     => $servis->montir_id,
                    'layanan_id'    => $layanan->id,
                    'nama_layanan'  => $layanan->nama_layanan ?? null,
                    'biaya_standar' => $layanan->biaya_standar ?? null,
                ]
            );
        }

        $this->recalculateTotal($servis);

        return back()->with('success', 'Layanan berhasil ditambahkan.');
    }

    /** HAPUS LAYANAN OLEH MONTIR */
    public function destroyLayanan(Servis $servis, Layanan $layanan)
    {
        if (! in_array($servis->status_servis, ['menunggu', 'dikerjakan'])) {
            return back()->with('error', 'Layanan tidak bisa dihapus pada status ini.');
        }

        if ($servis->montir_id !== Auth::id()) {
            abort(403, 'Servis ini bukan tugas Anda.');
        }

        $servis->detail_layanans()->detach($layanan->id);

        ActivityLogger::log(
            'Montir menghapus layanan dari servis',
            $servis,
            [
                'montir_id'    => $servis->montir_id,
                'layanan_id'   => $layanan->id,
                'nama_layanan' => $layanan->nama_layanan ?? null,
            ]
        );

        $this->recalculateTotal($servis);

        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    /* ===========================================
     *  SPAREPART OLEH MONTIR (TAMBAH / HAPUS)
     * =========================================== */

    public function storeSparepart(Request $request, Servis $servis)
    {
        if (! in_array($servis->status_servis, ['menunggu', 'dikerjakan'])) {
            return back()->with('error', 'Sparepart tidak bisa diubah pada status ini.');
        }

        if ($servis->montir_id !== Auth::id()) {
            abort(403, 'Servis ini bukan tugas Anda.');
        }

        $data = $request->validate([
            'sparepart_id' => ['required', 'exists:spareparts,id'],
            'jumlah'       => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($servis, $data) {
            /** @var \App\Models\Sparepart $sparepart */
            $sparepart = Sparepart::lockForUpdate()->findOrFail($data['sparepart_id']);

            // Ambil harga jual dari master
            $hargaSatuan = $sparepart->harga_jual;

            if ($hargaSatuan === null) {
                throw new \RuntimeException('Harga jual sparepart belum diset.');
            }

            // Cek stok cukup
            if ($sparepart->stok < $data['jumlah']) {
                throw new \RuntimeException('Stok sparepart tidak mencukupi.');
            }

            $subtotal = $data['jumlah'] * $hargaSatuan;

            // Tambah sparepart ke pivot (boleh lebih dari satu entri untuk sparepart sama)
            $servis->spareparts()->attach($sparepart->id, [
                'jumlah'       => $data['jumlah'],
                'harga_satuan' => $hargaSatuan,
                'subtotal'     => $subtotal,
            ]);

            // Kurangi stok global
            $sparepart->decreaseStock($data['jumlah']);

            // LOG aktivitas montir
            ActivityLogger::log(
                'Montir menambahkan sparepart ke servis',
                $servis,
                [
                    'montir_id'      => $servis->montir_id,
                    'sparepart_id'   => $sparepart->id,
                    'nama_sparepart' => $sparepart->nama_sparepart,
                    'qty_tambah'     => $data['jumlah'],
                    'harga_satuan'   => $hargaSatuan,
                    'subtotal'       => $subtotal,
                    'stok_sisa'      => $sparepart->stok,
                ]
            );
        });

        // Hitung ulang total
        $this->recalculateTotal($servis->fresh(['detail_layanans', 'spareparts']));

        return back()->with('success', 'Sparepart berhasil ditambahkan dan stok dikurangi.');
    }

    public function destroySparepart(Servis $servis, Sparepart $sparepart)
    {
        if (! in_array($servis->status_servis, ['menunggu', 'dikerjakan'])) {
            return back()->with('error', 'Sparepart tidak bisa dihapus pada status ini.');
        }

        if ($servis->montir_id !== Auth::id()) {
            abort(403, 'Servis ini bukan tugas Anda.');
        }

        DB::transaction(function () use ($servis, $sparepart) {
            // Ambil satu baris pivot untuk sparepart ini
            $pivotRow = $servis->spareparts()
                ->where('sparepart_id', $sparepart->id)
                ->firstOrFail()
                ->pivot;

            $jumlah = $pivotRow->jumlah;

            // Lepas pivot
            $servis->spareparts()->detach($sparepart->id);

            // Kembalikan stok global
            $sparepart->increaseStock($jumlah);

            // LOG aktivitas
            ActivityLogger::log(
                'Montir menghapus sparepart dari servis',
                $servis,
                [
                    'montir_id'      => $servis->montir_id,
                    'sparepart_id'   => $sparepart->id,
                    'nama_sparepart' => $sparepart->nama_sparepart,
                    'qty_dihapus'    => $jumlah,
                    'stok_setelah'   => $sparepart->stok,
                ]
            );
        });

        // Hitung ulang total
        $this->recalculateTotal($servis->fresh(['detail_layanans', 'spareparts']));

        return back()->with('success', 'Sparepart dihapus dan stok dikoreksi.');
    }

    /* ===========================================
     *  HITUNG ULANG TOTAL BIAYA SERVIS
     * =========================================== */

    private function recalculateTotal(Servis $servis): void
    {
        $servis->loadMissing(['detail_layanans', 'spareparts']);

        $totalJasa = $servis->detail_layanans->sum('biaya_standar');

        $totalPart = $servis->spareparts->sum(function ($part) {
            return $part->pivot->subtotal;
        });

        $servis->total_biaya = $totalJasa + $totalPart;
        $servis->save();
    }
}
