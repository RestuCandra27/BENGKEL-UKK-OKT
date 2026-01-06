<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'invoice_id',
        'pelanggan_id',
        'jumlah_bayar',
        'metode_bayar',
        'tanggal_bayar',
        'catatan',
        'catatan_admin',
        'bukti_path',
        'status',
        'verified_by',
        'verified_at',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'pelanggan_id');
    }
}
