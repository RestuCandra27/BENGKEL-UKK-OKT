<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_sku',
        'nama_sparepart',
        'merek',
        'kategori',
        'stok',
        'harga_jual',
    ];

    protected $casts = [
        'stok'       => 'integer',
        'harga_jual' => 'integer',
    ];

    // ==========================
    // RELASI
    // ==========================

    // Riwayat stok masuk (pembelian)
    public function pembelian()
    {
        return $this->hasMany(PembelianSparepart::class);
    }

    // Servis yang menggunakan sparepart ini
    public function servis()
    {
        return $this->belongsToMany(Servis::class, 'servis_sparepart')
                    ->withPivot(['jumlah', 'harga_satuan', 'subtotal'])
                    ->withTimestamps();
    }

    // ==========================
    // LOGIKA STOK
    // ==========================

    /**
     * Tambah stok.
     */
    public function increaseStock(int $qty): void
    {
        $this->increment('stok', $qty);
    }

    /**
     * Kurangi stok.
     *
     * @throws \InvalidArgumentException jika stok tidak cukup
     */
    public function decreaseStock(int $qty): void
    {
        if ($qty > $this->stok) {
            throw new \InvalidArgumentException('Stok sparepart tidak mencukupi.');
        }

        $this->decrement('stok', $qty);
    }

    // ==========================
    // HELPER TAMPILAN
    // ==========================

    public function getStokLabelAttribute(): string
    {
        return $this->stok > 0
            ? $this->stok . ' pcs'
            : 'Habis';
    }

    // Penanda stok kritis (misal <= 5)
    public function getIsLowStockAttribute(): bool
    {
        return $this->stok <= 5;
    }
}
