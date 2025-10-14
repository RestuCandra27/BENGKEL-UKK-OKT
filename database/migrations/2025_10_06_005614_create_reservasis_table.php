<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('Reservasi', function (Blueprint $table) {
            $table->id('id_reservasi');
            $table->foreignId('id_user')->constrained('User', 'id_user');
            $table->foreignId('id_kendaraan')->constrained('Kendaraan', 'id_kendaraan');
            $table->date('tanggal_booking');
            $table->time('jam_booking');
            $table->text('keluhan_awal')->nullable();
            $table->enum('status_reservasi', ['Dijadwalkan', 'Selesai', 'Batal'])->default('Dijadwalkan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
