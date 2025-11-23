<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{
    use HasFactory;

    protected $table = 'servis'; // Sesuai nama tabel di database

    protected $fillable = [
        'user_id',
        'kendaraan_id',
        'montir_id',
        'keluhan', // <-- Pastikan kolom ini ada, atau gunakan 'catatan_masuk' jika di database beda
        'tanggal_servis',
        'status_servis',
        'total_biaya'
    ];

    public function pelanggan() { return $this->belongsTo(User::class, 'user_id'); }
    public function kendaraan() { return $this->belongsTo(Kendaraan::class, 'kendaraan_id'); }
    public function montir()    { return $this->belongsTo(User::class, 'montir_id'); }
}