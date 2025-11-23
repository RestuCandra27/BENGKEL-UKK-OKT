<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servis', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke data pelanggan
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            // Relasi ke data kendaraan
            $table->foreignId('kendaraan_id')->constrained('kendaraans')->onDelete('cascade'); 
            // Relasi ke data montir (dari tabel users juga)
            $table->foreignId('montir_id')->constrained('users')->onDelete('cascade'); 
            
            // Jika servis ini dibuat dari booking, isi ini
            $table->foreignId('reservasi_id')->nullable()->constrained('reservasis')->onDelete('set null'); 
            $table->text('keluhan');

            $table->date('tanggal_servis');
            $table->enum('status_servis', ['menunggu', 'dikerjakan', 'selesai', 'dibayar', 'dibatalkan'])->default('menunggu');
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('servis');
    }
};
