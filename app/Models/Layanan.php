<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    /**
     * Daftar kolom yang boleh diisi.
     */
    protected $fillable = [
        'nama_layanan',
        'biaya_standar',
        'deskripsi',
    ];
}