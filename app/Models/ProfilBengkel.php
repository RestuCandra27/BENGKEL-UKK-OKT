<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilBengkel extends Model
{
    // kalau nama tabel-mu bukan "profil_bengkels", set manual:
    protected $table = 'profil_bengkel';

    protected $fillable = [
        'nama_bengkel',
        'deskripsi_singkat',
        'deskripsi_panjang',
        'alamat',
        'jam_operasional',
        'tahun_berdiri',
        'nomor_wa',
        'telepon',
        'email',
    ];
}
