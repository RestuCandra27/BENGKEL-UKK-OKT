<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/..._create_montirs_table.php
    public function up()
    {
        Schema::create('Montir', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->primary();
            $table->foreign('id_user')->references('id_user')->on('User')->onDelete('cascade');
            
            $table->string('kode_montir', 10)->unique();
            $table->text('alamat')->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('spesialisasi', 50)->nullable();
            $table->enum('level', ['Junior', 'Senior', 'Kepala Montir']);
            $table->date('tanggal_bergabung');
            $table->string('kontak_darurat_nama', 100)->nullable();
            $table->string('kontak_darurat_nomor', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('montirs');
    }
};
