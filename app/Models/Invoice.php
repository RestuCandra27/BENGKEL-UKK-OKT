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
        'status_pembayaran'
    ];

    public function servis()
    {
        return $this->belongsTo(Servis::class, 'servis_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }
}
