<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'Pelanggan';
    protected $primaryKey = 'id_user';
    public $incrementing = false;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_user',
        'no_hp',
        'alamat',
        'jenis_member',
        'pekerjaan',
        'tanggal_lahir',
        'jenis_kelamin',
    ];

    /**
     * Mendapatkan data user yang memiliki profil ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}

