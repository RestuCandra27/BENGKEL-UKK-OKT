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
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID Pelanggan
            $table->foreignId('kendaraan_id')->constrained('kendaraans')->onDelete('cascade');
            $table->date('tanggal_booking');
            $table->time('jam_booking');
            $table->text('keluhan_awal')->nullable();
            $table->enum('status_reservasi', ['Dijadwalkan', 'Selesai', 'Batal'])->default('Dijadwalkan');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
