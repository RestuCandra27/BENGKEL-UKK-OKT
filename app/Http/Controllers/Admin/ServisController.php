<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servis;
use App\Models\User;
use App\Models\Kendaraan;
use App\Models\Layanan;
use App\Models\PaketServis;
use App\Models\Sparepart;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $servis = Servis::create([
            'user_id'        => $request->user_id,
            'kendaraan_id'   => $request->kendaraan_id,
            'montir_id'      => $request->montir_id,
            'keluhan'        => $request->keluhan,
            'tanggal_servis' => $request->tanggal_servis,
            'status_servis'  => 'menunggu',
            'total_biaya'    => 0,
        ]);

        // 🔎 LOG: pendaftaran servis baru
        ActivityLogger::log(
            'Pendaftaran servis baru',
            $servis,
            [
                'pelanggan_id'  => $servis->user_id,
                'kendaraan_id'  => $servis->kendaraan_id,
                'montir_id'     => $servis->montir_id,
                'tanggal_servis'=> $servis->tanggal_servis,
                'status'        => $servis->status_servis,
            ]
        );

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
            'spareparts',
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
     *  UPDATE STATUS SERVIS
     *  ============================ */
    public function update(Request $request, $id)
    {
        $servis = Servis::findOrFail($id);

        $request->validate([
            'status_servis' => 'required|in:menunggu,dikerjakan,selesai,dibayar,dibatalkan',
            'keluhan'       => 'required|string',
        ]);

        $oldStatus  = $servis->status_servis;
        $oldKeluhan = $servis->keluhan;

        $servis->update([
            'status_servis' => $request->status_servis,
            'keluhan'       => $request->keluhan,
        ]);

        // 🔎 LOG: update status servis
        ActivityLogger::log(
            'Update status servis',
            $servis,
            [
                'status_lama'  => $oldStatus,
                'status_baru'  => $servis->status_servis,
                'keluhan_lama' => $oldKeluhan,
                'keluhan_baru' => $servis->keluhan,
            ]
        );

        return redirect()->route('admin.servis.index')
            ->with('success', 'Status servis berhasil diperbarui.');
    }

    /** ============================
     *  HAPUS SERVIS + KEMBALIKAN STOK
     *  ============================ */
    public function destroy($id)
    {
        $servis = Servis::with('spareparts')->findOrFail($id);

        DB::transaction(function () use ($servis) {

            $ringkasanSparepart = [];

            // Kembalikan stok semua sparepart yang terpakai
            foreach ($servis->spareparts as $part) {
                $jumlah = $part->pivot->jumlah ?? 0;
                if ($jumlah > 0) {
                    $part->increaseStock($jumlah);

                    $ringkasanSparepart[] = [
                        'sparepart_id'   => $part->id,
                        'nama_sparepart' => $part->nama_sparepart,
                        'jumlah_dikembalikan' => $jumlah,
                    ];
                }
            }

            // Simpan data untuk log sebelum delete
            $logData = [
                'pelanggan_id' => $servis->user_id,
                'kendaraan_id' => $servis->kendaraan_id,
                'montir_id'    => $servis->montir_id,
                'status'       => $servis->status_servis,
                'total_biaya'  => $servis->total_biaya,
                'spareparts'   => $ringkasanSparepart,
            ];

            // Hapus relasi pivot
            $servis->detail_layanans()->detach();
            $servis->spareparts()->detach();

            // Hapus servis
            $servis->delete();

            // 🔎 LOG: hapus servis
            ActivityLogger::log(
                'Hapus servis (beserta koreksi stok sparepart)',
                $servis,
                $logData
            );
        });

        return redirect()->route('admin.servis.index')
            ->with('success', 'Data servis berhasil dihapus dan stok sparepart dikoreksi.');
    }

    /** ============================
     *  TAMBAH LAYANAN ATAU PAKET SERVIS
     *  ============================ */
    public function storeLayanan(Request $request, Servis $servis)
    {
        $layananBaru = [];
        $paketDipakai = null;

        // Tambah LAYANAN biasa
        if ($request->filled('layanan_id')) {
            $request->validate([
                'layanan_id' => 'required|exists:layanans,id',
            ]);

            $sudahAda = $servis->detail_layanans()
                ->where('layanans.id', $request->layanan_id)
                ->exists();

            if (! $sudahAda) {
                $servis->detail_layanans()->attach($request->layanan_id);
                $layanan = Layanan::find($request->layanan_id);

                $layananBaru[] = [
                    'layanan_id'    => $layanan->id,
                    'nama_layanan'  => $layanan->nama_layanan,
                    'biaya_standar' => $layanan->biaya_standar,
                    'sumber'        => 'single',
                ];
            }
        }

        // Tambah PAKET SERVIS
        if ($request->filled('paket_servis_id')) {
            $request->validate([
                'paket_servis_id' => 'required|exists:paket_servis,id',
            ]);

            $paket = PaketServis::with('layanans')->findOrFail($request->paket_servis_id);
            $paketDipakai = [
                'paket_id'   => $paket->id,
                'nama_paket' => $paket->nama_paket ?? null,
            ];

            foreach ($paket->layanans as $layan) {
                $sudahAda = $servis->detail_layanans()
                    ->where('layanans.id', $layan->id)
                    ->exists();

                if (! $sudahAda) {
                    $servis->detail_layanans()->attach($layan->id);

                    $layananBaru[] = [
                        'layanan_id'    => $layan->id,
                        'nama_layanan'  => $layan->nama_layanan,
                        'biaya_standar' => $layan->biaya_standar,
                        'sumber'        => 'paket',
                    ];
                }
            }
        }

        // Hitung ulang total biaya setelah ada perubahan layanan
        $this->hitungTotalBiaya($servis);

        // 🔎 LOG: tambah layanan / paket
        if (!empty($layananBaru)) {
            ActivityLogger::log(
                'Tambah layanan ke servis',
                $servis,
                [
                    'layanan_baru' => $layananBaru,
                    'paket_dipakai'=> $paketDipakai,
                    'total_biaya_baru' => $servis->fresh()->total_biaya,
                ]
            );
        }

        return back()->with('success', 'Layanan atau paket servis berhasil ditambahkan.');
    }

    /** ============================
     *  HAPUS LAYANAN
     *  ============================ */
    public function destroyLayanan(Servis $servis, $layananId)
    {
        $layanan = Layanan::find($layananId);

        $servis->detail_layanans()->detach($layananId);

        $this->hitungTotalBiaya($servis);

        // 🔎 LOG: hapus layanan dari servis
        ActivityLogger::log(
            'Hapus layanan dari servis',
            $servis,
            [
                'layanan_id'    => $layananId,
                'nama_layanan'  => $layanan->nama_layanan ?? null,
                'total_biaya_baru' => $servis->fresh()->total_biaya,
            ]
        );

        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    /** ============================
     *  TAMBAH SPAREPART
     *  ============================ */
    public function storeSparepart(Request $request, Servis $servis)
    {
        $data = $request->validate([
            'sparepart_id' => ['required', 'exists:spareparts,id'],
            'jumlah'       => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($servis, $data) {
            // Kunci sparepart supaya stok aman dari race condition
            $sparepart = Sparepart::lockForUpdate()->findOrFail($data['sparepart_id']);

            // Cek stok cukup
            if ($sparepart->stok < $data['jumlah']) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'jumlah' => 'Stok sparepart tidak mencukupi. Sisa stok: ' . $sparepart->stok,
                ]);
            }

            // Cek apakah sparepart ini sudah pernah ditambahkan ke servis ini
            $existing = $servis->spareparts()
                ->where('sparepart_id', $sparepart->id)
                ->first();

            $mode       = 'baru';
            $qtyTambah  = $data['jumlah'];
            $qtyAkhir   = $qtyTambah;
            $hargaSatuan= $sparepart->harga_jual ?? 0;
            $subtotal   = 0;

            if ($existing) {
                // Kalau sudah ada → tambah jumlah
                $currentQty  = $existing->pivot->jumlah;
                $qtyAkhir    = $currentQty + $data['jumlah'];
                $hargaSatuan = $existing->pivot->harga_satuan; // pakai harga yang sama
                $subtotal    = $qtyAkhir * $hargaSatuan;

                $servis->spareparts()->updateExistingPivot($sparepart->id, [
                    'jumlah'   => $qtyAkhir,
                    'subtotal' => $subtotal,
                ]);

                $mode = 'tambah_qty';
            } else {
                // Kalau belum ada → buat baris baru
                $hargaSatuan = $sparepart->harga_jual ?? 0;
                $subtotal    = $hargaSatuan * $data['jumlah'];

                $servis->spareparts()->attach($sparepart->id, [
                    'jumlah'       => $data['jumlah'],
                    'harga_satuan' => $hargaSatuan,
                    'subtotal'     => $subtotal,
                ]);
            }

            // Kurangi stok sebesar jumlah yang BARU ditambahkan
            $sparepart->decreaseStock($data['jumlah']);

            // 🔎 LOG: tambah / ubah sparepart di servis
            ActivityLogger::log(
                'Tambah sparepart ke servis',
                $servis,
                [
                    'mode'           => $mode,
                    'sparepart_id'   => $sparepart->id,
                    'nama_sparepart' => $sparepart->nama_sparepart,
                    'qty_tambah'     => $qtyTambah,
                    'qty_akhir'      => $qtyAkhir,
                    'harga_satuan'   => $hargaSatuan,
                    'subtotal_baris' => $subtotal,
                    'stok_sisa'      => $sparepart->stok,
                ]
            );
        });

        // Hitung ulang total biaya servis
        $this->hitungTotalBiaya($servis->fresh());

        return back()->with('success', 'Sparepart berhasil ditambahkan dan stok diperbarui.');
    }

    /** ============================
     *  HAPUS SPAREPART
     *  ============================ */
    public function destroySparepart(Servis $servis, Sparepart $sparepart)
    {
        DB::transaction(function () use ($servis, $sparepart) {

            $record = $servis->spareparts()
                ->where('sparepart_id', $sparepart->id)
                ->firstOrFail();

            $pivot  = $record->pivot;
            $jumlah = $pivot->jumlah;

            // Kembalikan stok
            $sparepart->increment('stok', $jumlah);

            // Lepas dari servis
            $servis->spareparts()->detach($sparepart->id);

            // 🔎 LOG: hapus sparepart dari servis
            ActivityLogger::log(
                'Hapus sparepart dari servis',
                $servis,
                [
                    'sparepart_id'   => $sparepart->id,
                    'nama_sparepart' => $sparepart->nama_sparepart,
                    'qty_dihapus'    => $jumlah,
                    'stok_setelah'   => $sparepart->stok,
                ]
            );
        });

        $this->hitungTotalBiaya($servis);

        return back()->with('success', 'Sparepart berhasil dihapus. Stok dikembalikan.');
    }

    /** ============================
     *  HITUNG TOTAL BIAYA SERVIS
     *  ============================ */
    private function hitungTotalBiaya(Servis $servis)
    {
        $servis->load(['detail_layanans', 'spareparts']);

        // Total jasa: sum biaya_standar semua layanan
        $totalJasa = $servis->detail_layanans->sum('biaya_standar');

        // Total sparepart: cukup pakai subtotal dari pivot
        $totalPart = $servis->spareparts->sum(function ($part) {
            return $part->pivot->subtotal;
        });

        $servis->update([
            'total_biaya' => $totalJasa + $totalPart,
        ]);
    }
}
