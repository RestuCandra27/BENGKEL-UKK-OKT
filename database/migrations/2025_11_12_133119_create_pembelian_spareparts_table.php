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
        Schema::create('pembelian_spareparts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sparepart_id')->constrained('spareparts')->onDelete('cascade');
            $table->date('tanggal_masuk');
            $table->integer('jumlah_masuk');
            $table->integer('stok_tersisa');
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('harga_jual', 15, 2); // Harga jual untuk batch ini
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pembelian_spareparts');
    }
};
