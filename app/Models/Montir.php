<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Montir extends Model
{
    use HasFactory;

    protected $table = 'Montir';
    protected $primaryKey = 'id_user';
    public $incrementing = false;
    public $timestamps = false;

    /**
     * Mendapatkan data user yang memiliki profil ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}