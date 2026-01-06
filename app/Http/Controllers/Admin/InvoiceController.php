<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Servis;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /** LIST INVOICE */
    public function index()
    {
        $invoices = Invoice::with(['pelanggan', 'servis'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.invoices.index', compact('invoices'));
    }

    /** TAMPILKAN DETAIL INVOICE (ADMIN) */
    public function show($id)
    {
        $invoice = Invoice::with([
                'pelanggan',
                // include detail_layanans & spareparts, TANPA .layanan
                'servis.detail_layanans',
                'servis.spareparts',
                'payments',
            ])
            ->findOrFail($id);

        // Total sudah dibayar & sisa tagihan
        $totalBayar  = $invoice->payments->sum('jumlah_bayar');
        $sisaTagihan = max($invoice->total_tagihan - $totalBayar, 0);

        return view('admin.invoices.show', compact('invoice', 'totalBayar', 'sisaTagihan'));
    }

    /** BUAT INVOICE BARU (DARI SERVIS) */
    public function store(Request $request)
    {
        $request->validate([
            'servis_id' => 'required|exists:servis,id',
        ]);

        // Ambil servis beserta detail layanan, sparepart, dan pelanggan
        $servis = Servis::with(['detail_layanans', 'spareparts', 'pelanggan'])
            ->findOrFail($request->servis_id);

        // Cek minimal 1 layanan atau sparepart
        if ($servis->detail_layanans->isEmpty() && $servis->spareparts->isEmpty()) {
            return back()
                ->with('error', 'Tidak bisa membuat invoice. Tambahkan minimal satu layanan atau sparepart pada servis terlebih dahulu.')
                ->withInput();
        }

        // Total biaya layanan
        $totalLayanan = $servis->detail_layanans->sum('biaya_standar');

        // Total biaya sparepart: jumlah * harga_satuan
        $totalSparepart = $servis->spareparts->sum(function ($sp) {
            return ($sp->pivot->jumlah ?? 0) * ($sp->pivot->harga_satuan ?? 0);
        });

        $subtotal = $totalLayanan + $totalSparepart;

        if ($subtotal <= 0) {
            return back()
                ->with('error', 'Total tagihan masih 0. Pastikan layanan atau sparepart pada servis sudah diatur dengan benar.')
                ->withInput();
        }

        // Diskon berdasarkan jenis member
        $jenisMember = $servis->pelanggan->jenis_member ?? 'Reguler';

        $diskonLayananPersen   = 0;
        $diskonSparepartPersen = 0;

        if ($jenisMember === 'VIP') {
            $diskonLayananPersen   = 10; // 10% jasa
            $diskonSparepartPersen = 5;  // 5% sparepart
        } elseif ($jenisMember === 'Fleet') {
            $diskonLayananPersen   = 15;
            $diskonSparepartPersen = 10;
        }

        $diskonLayanan   = $totalLayanan * $diskonLayananPersen / 100;
        $diskonSparepart = $totalSparepart * $diskonSparepartPersen / 100;

        $totalSetelahDiskon = ($totalLayanan - $diskonLayanan)
                            + ($totalSparepart - $diskonSparepart);

        // BUAT INVOICE
        $invoice = Invoice::create([
            'servis_id'             => $servis->id,
            'user_id'               => $servis->user_id,
            'nomor_invoice'         => 'INV-' . time(),
            'total_biaya_layanan'   => $totalLayanan,       // sebelum diskon
            'total_biaya_sparepart' => $totalSparepart,     // sebelum diskon
            'total_tagihan'         => $totalSetelahDiskon, // sesudah diskon
            'status_pembayaran'     => 'Belum Lunas',
        ]);

        // LOG AKTIVITAS
        ActivityLogger::log(
            'Buat invoice',
            'Invoice',
            $invoice->id,
            [
                'servis_id'        => $servis->id,
                'pelanggan'        => $servis->pelanggan->nama ?? '-',
                'total_layanan'    => $totalLayanan,
                'total_sparepart'  => $totalSparepart,
                'total_tagihan'    => $totalSetelahDiskon,
                'member'           => $jenisMember,
            ]
        );

        return redirect()
            ->route('admin.invoices.show', $invoice->id)
            ->with('success', 'Invoice berhasil dibuat.');
    }

    /** CETAK NOTA PEMBAYARAN (ADMIN) */
    public function nota($id)
    {
        $invoice = Invoice::with([
                'pelanggan',
                // perhatikan: TIDAK ada ".layanan" di sini
                'servis.detail_layanans',
                'servis.spareparts',
                'payments',
            ])
            ->findOrFail($id);

        return view('admin.invoices.nota', compact('invoice'));
    }
}
