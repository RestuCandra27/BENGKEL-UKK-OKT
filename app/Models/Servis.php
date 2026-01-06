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
        'reservasi_id',
        'keluhan',
        'catatan_montir',
        'tanggal_servis',
        'status_servis', // menunggu, dikerjakan, selesai, dibayar, dibatalkan
        'total_biaya',
    ];

    protected $casts = [
        'tanggal_servis' => 'date',
        'total_biaya'    => 'integer',
    ];

    // ==========================
    // RELASI UTAMA
    // ==========================

    // Pelanggan yang melakukan servis
    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Kendaraan yang diservis
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    // Montir yang menangani servis
    public function montir()
    {
        return $this->belongsTo(User::class, 'montir_id');
    }

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id');
    }


    // ==========================
    // RELASI DETAIL (PIVOT)
    // ==========================

    // Daftar layanan yang dipilih untuk servis ini
    public function detail_layanans()
    {
        return $this->belongsToMany(Layanan::class, 'detail_servis_layanans', 'servis_id', 'layanan_id')
            ->withTimestamps();
    }

    // Daftar sparepart yang digunakan di servis ini
    public function spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'servis_sparepart')
            ->withPivot(['jumlah', 'harga_satuan', 'subtotal'])
            ->withTimestamps();
    }

    public function riwayat_kondisi()
    {
        return $this->hasOne(RiwayatKondisi::class, 'servis_id');
    }

    // ==========================
    // HELPER (OPSIONAL)
    // ==========================

    public function getTanggalServisFormattedAttribute(): ?string
    {
        return $this->tanggal_servis?->format('d-m-Y');
    }
}
