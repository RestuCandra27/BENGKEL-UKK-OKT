<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianSparepart extends Model
{
    use HasFactory;

    protected $table = 'pembelian_spareparts';

    protected $fillable = [
        'sparepart_id',
        'tanggal_masuk',
        'jumlah_masuk',
        'stok_tersisa',
        'harga_beli',
        'harga_jual',
        'keterangan',
    ];
    protected $casts = [
        'tanggal_masuk' => 'date',
        'jumlah_masuk'  => 'integer',
        'harga_beli'    => 'integer',
    ];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }

    public function getTanggalMasukFormattedAttribute()
    {
        return $this->tanggal_masuk?->format('d-m-Y');
    }
}
