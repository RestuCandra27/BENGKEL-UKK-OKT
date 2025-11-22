<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketServis extends Model
{
    use HasFactory;

    // Sesuaikan dengan nama tabel di migrasi baru
    protected $table = 'paket_servis';

    protected $fillable = [
        'kode_paket',
        'nama_paket',
        'deskripsi',
        'harga_paket',
    ];

    // RELASI PENTING: Many-to-Many ke Layanan
    // Artinya: Satu paket bisa punya banyak layanan
    public function layanans()
    {
        // Parameter: (Model Tujuan, Nama Tabel Pivot, FK di Pivot utk Model Ini, FK di Pivot utk Model Tujuan)
        return $this->belongsToMany(Layanan::class, 'detail_paket_layanan', 'paket_servis_id', 'layanan_id');
    }
}