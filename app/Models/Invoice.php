<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'servis_id',
        'user_id',
        'nomor_invoice',
        'total_biaya_layanan',
        'total_biaya_sparepart',
        'total_tagihan',
        'status_pembayaran',
    ];

    // Relasi ke servis
    public function servis()
    {
        return $this->belongsTo(Servis::class, 'servis_id');
    }

    // Relasi ke pelanggan (user)
    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke payments (tabel payments lama yang sudah ada)
    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }

    // === Helper otomatis ===

    // Total yang sudah dibayar untuk invoice ini
    public function getTotalDibayarAttribute()
    {
        return $this->payments->sum('jumlah_bayar');
    }

    // Sisa tagihan (tidak boleh minus)
    public function getSisaTagihanAttribute()
    {
        return max($this->total_tagihan - $this->total_dibayar, 0);
    }
}
