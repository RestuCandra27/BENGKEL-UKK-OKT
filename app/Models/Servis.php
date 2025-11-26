<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{
    use HasFactory;

    protected $table = 'servis';

    protected $fillable = [
        'user_id',
        'kendaraan_id',
        'montir_id',
        'keluhan',
        'tanggal_servis',
        'status_servis', // menunggu, dikerjakan, selesai, dibayar, dibatalkan
        'total_biaya'
    ];

    // --- RELASI UTAMA ---

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    public function montir()
    {
        return $this->belongsTo(User::class, 'montir_id');
    }

    // --- RELASI DETAIL (PIVOT) ---

    // Mengambil daftar Layanan yang dipilih untuk servis ini
    public function detail_layanans()
    {
        return $this->belongsToMany(Layanan::class, 'detail_servis_layanans', 'servis_id', 'layanan_id')
                    ->withTimestamps();
    }

    // Mengambil daftar Sparepart yang digunakan
    // withPivot mengambil kolom tambahan di tabel penghubung
    public function detail_spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'penggunaan_spareparts', 'servis_id', 'sparepart_id')
                    ->withPivot('jumlah_digunakan', 'harga_saat_digunakan', 'pembelian_sparepart_id')
                    ->withTimestamps();
    }
}
