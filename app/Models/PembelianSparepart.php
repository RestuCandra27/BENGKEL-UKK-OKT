<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianSparepart extends Model
{
    use HasFactory;

    // Konvensi nama tabel
    protected $table = 'pembelian_spareparts';

    protected $fillable = [
        'sparepart_id',
        'tanggal_masuk',
        'jumlah_masuk',
        'stok_tersisa', // Penting untuk sistem FIFO nanti
        'harga_beli',
        'harga_jual',
    ];

    // Relasi: Satu pembelian itu milik satu jenis Sparepart
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}