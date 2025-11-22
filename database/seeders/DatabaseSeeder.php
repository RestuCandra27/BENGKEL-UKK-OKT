<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil semua Seeder Anda di sini
        $this->call([
            UserSeeder::class,
            // (Nanti kita bisa tambahkan Seeder lain di sini)
        ]);
    }
}