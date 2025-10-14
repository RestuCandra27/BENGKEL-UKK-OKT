<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'Kendaraan';
    protected $primaryKey = 'id_kendaraan';
    public $timestamps = false;

    /**
     * Mendapatkan user pemilik kendaraan ini.
     */
    public function pemilik()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}