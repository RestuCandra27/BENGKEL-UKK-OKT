<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('Servis', function (Blueprint $table) {
            $table->id('id_servis');
            $table->foreignId('id_reservasi')->nullable()->constrained('Reservasi', 'id_reservasi');
            $table->foreignId('id_user')->constrained('User', 'id_user');
            $table->foreignId('id_kendaraan')->constrained('Kendaraan', 'id_kendaraan');
            $table->foreignId('id_montir')->constrained('User', 'id_user');
            $table->date('tanggal_servis');
            $table->enum('status_servis', ['menunggu', 'dikerjakan', 'selesai'])->default('menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servis');
    }
};
