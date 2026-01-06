<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatKondisi extends Model
{
    protected $table = 'riwayat_kondisis'; // nama tabel di DB kamu

    protected $fillable = [
        'servis_id',
        'catatan_saat_masuk',
        'catatan_setelah_selesai',
        'rekomendasi_montir',
    ];

    public function servis()
    {
        return $this->belongsTo(Servis::class, 'servis_id');
    }
}
