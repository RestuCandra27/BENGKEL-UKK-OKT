<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // <-- Pastikan ini ada
use Illuminate\Support\Facades\Hash; // <-- Pastikan ini ada

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Membuat 10 user 'pelanggan' acak
        User::factory(10)->create();

        // 2. Membuat 1 Akun Admin Tetap
        // Kita menggunakan create() agar bisa menentukan datanya secara spesifik
        User::create([
            'nama' => 'Admin Bengkel',
            'email' => 'admin@bengkel.com',
            'password' => Hash::make('password'), // passwordnya adalah 'password'
            'role' => 'admin',
        ]);
        
        // 3. (Opsional) Membuat 1 Akun Montir Tetap
        User::create([
            'nama' => 'Montir Handal',
            'email' => 'montir@bengkel.com',
            'password' => Hash::make('password'),
            'role' => 'montir',
        ]);
    }
}
