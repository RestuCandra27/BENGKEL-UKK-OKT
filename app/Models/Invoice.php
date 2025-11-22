<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_invoice';

    /**
     * Mendapatkan data servis yang menghasilkan invoice ini.
     */
    public function servis()
    {
        return $this->belongsTo(Servis::class, 'id_servis', 'id_servis');
    }

    /**
     * Mendapatkan semua pembayaran untuk invoice ini.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'id_invoice', 'id_invoice');
    }
}