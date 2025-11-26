<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Servis;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /** LIST INVOICE */
    public function index()
    {
        $invoices = Invoice::with('pelanggan', 'servis')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.invoices.index', compact('invoices'));
    }

    /** TAMPILKAN DETAIL INVOICE */
    public function show($id)
    {
        $invoice = Invoice::with(['pelanggan', 'payments'])->findOrFail($id);

        // Total yang sudah dibayar & sisa tagihan (dipakai di view)
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
        $servis = Servis::with(['detail_layanans', 'detail_spareparts', 'pelanggan'])
            ->findOrFail($request->servis_id);

        // ⛔ CEK: jika tidak ada layanan dan tidak ada sparepart → TIDAK BOLEH buat invoice
        if ($servis->detail_layanans->isEmpty() && $servis->detail_spareparts->isEmpty()) {
            return back()
                ->with('error', 'Tidak bisa membuat invoice. Tambahkan minimal satu layanan atau sparepart pada servis terlebih dahulu.')
                ->withInput();
        }

        // HITUNG total biaya layanan (termasuk yang asalnya dari paket servis, karena sudah masuk detail_layanans)
        $totalLayanan = $servis->detail_layanans->sum('biaya_standar');

        // HITUNG total biaya sparepart
        $totalSparepart = 0;
        foreach ($servis->detail_spareparts as $sp) {
            $totalSparepart += $sp->pivot->jumlah_digunakan * $sp->pivot->harga_saat_digunakan;
        }

        $subtotal = $totalLayanan + $totalSparepart;

        // Jaga-jaga kalau subtotal masih 0
        if ($subtotal <= 0) {
            return back()
                ->with('error', 'Total tagihan masih 0. Pastikan layanan atau sparepart pada servis sudah diatur dengan benar.')
                ->withInput();
        }

        // 🔥 DISKON BERDASARKAN JENIS MEMBER PELANGGAN
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

        return redirect()
            ->route('admin.invoices.show', $invoice->id)
            ->with('success', 'Invoice berhasil dibuat.');
    }
}
