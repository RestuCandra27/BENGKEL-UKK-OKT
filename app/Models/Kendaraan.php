<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraans'; // Sesuai migrasi kita

    protected $fillable = [
        'user_id',      // ID Pelanggan (Pemilik)
        'no_polisi',
        'merek',
        'model',
        'tahun',
        'warna',
        'nomor_rangka',
        'nomor_mesin',
    ];

    // Relasi: Kendaraan ini milik satu User (Pelanggan)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}