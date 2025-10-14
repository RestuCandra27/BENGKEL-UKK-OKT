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
        Schema::create('Pembelian_Sparepart', function (Blueprint $table) {
            $table->id('id_pembelian');    
            $table->foreignId('id_sparepart')->constrained('Sparepart', 'id_sparepart');
            $table->date('tanggal_masuk');
            $table->integer('jumlah_masuk');
            $table->integer('stok_tersisa');
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('harga_jual', 15, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_spareparts');
    }
};
