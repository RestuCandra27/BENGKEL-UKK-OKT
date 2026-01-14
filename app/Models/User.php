<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * PERBAIKAN: Kita HAPUS $table dan $primaryKey.
     * Dengan menghapusnya, Laravel akan otomatis menggunakan konvensi
     * (tabel 'users' dan primary key 'id'), yang sudah kita perbaiki.
     */
    // protected $table = 'User'; <-- HAPUS
    // protected $primaryKey = 'id_user'; <-- HAPUS

    /**
     * PERBAIKAN: $fillable diperbarui dengan SEMUA kolom
     * dari migrasi 'users' baru kita.
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'no_hp',
        'alamat',
        'profile_photo_path',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'jenis_member',
        'poin_loyalitas',
        'kode_montir',
        'spesialisasi',
        'level',
        'tanggal_bergabung',
        'kontak_darurat_nama',
        'kontak_darurat_nomor',
        'otp_code',
        'otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * (Ini sudah benar)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     * (Ini sudah benar, 'email_verified_at' ditambahkan)
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- PERBAIKAN: Relasi ---

    /**
     * PERBAIKAN: Relasi 'pelanggan' dan 'montir' DIHAPUS
     * karena datanya sudah ada di model 'User' ini.
     */
    // public function pelanggan() { ... } <-- HAPUS
    // public function montir() { ... } <-- HAPUS

    /**
     * PERBAIKAN: Relasi 'kendaraan' (sebagai pelanggan)
     * Laravel akan otomatis mencari 'user_id' (sesuai konvensi baru kita).
     */
    public function kendaraans() // Nama dibuat jamak
    {
        return $this->hasMany(Kendaraan::class, 'user_id');
    }

    /**
     * PERBAIKAN: Relasi 'servis' (sebagai pelanggan)
     */
    public function servis_sebagai_pelanggan()
    {
        return $this->hasMany(Servis::class, 'user_id');
    }

    /**
     * PERBAIKAN: Relasi 'servis' (sebagai montir)
     */
    public function servis_sebagai_montir()
    {
        return $this->hasMany(Servis::class, 'montir_id');
    }
}