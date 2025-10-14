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
        Schema::create('Paket_Servis', function (Blueprint $table) {
            $table->id('id_paket');
            $table->string('kode_paket', 20)->unique();
            $table->string('nama_paket', 100);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_paket', 15, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_servis');
    }
};
