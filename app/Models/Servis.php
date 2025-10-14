<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{
    use HasFactory;

    protected $table = 'Servis';
    protected $primaryKey = 'id_servis';

    /**
     * Mendapatkan data pelanggan (dari tabel User).
     */
    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Mendapatkan data montir (dari tabel User).
     */
    public function montir()
    {
        return $this->belongsTo(User::class, 'id_montir', 'id_user');
    }

    /**
     * Mendapatkan data kendaraan yang diservis.
     */
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan', 'id_kendaraan');
    }
    
    /**
     * Mendapatkan data invoice dari servis ini.
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id_servis', 'id_servis');
    }
}