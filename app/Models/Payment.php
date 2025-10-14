<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'Payment';
    protected $primaryKey = 'id_payment';
    public $timestamps = false;

    /**
     * Mendapatkan data invoice dari pembayaran ini.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'id_invoice', 'id_invoice');
    }

    /**
     * Mendapatkan data kasir yang menangani pembayaran.
     */
    public function kasir()
    {
        return $this->belongsTo(User::class, 'id_kasir', 'id_user');
    }
}