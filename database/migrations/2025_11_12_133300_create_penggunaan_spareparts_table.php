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
        Schema::create('penggunaan_spareparts', function (Blueprint $table) {
            $table->id(); // Kunci utama untuk tabel ini
            $table->foreignId('servis_id')->constrained('servis')->onDelete('cascade');
            $table->foreignId('sparepart_id')->constrained('spareparts')->onDelete('cascade');
            
            // Relasi ke batch pembelian spesifik (untuk FIFO)
            $table->foreignId('pembelian_sparepart_id')->constrained('pembelian_spareparts');
            
            $table->integer('jumlah_digunakan');
            $table->decimal('harga_saat_digunakan', 15, 2); // Catat harga jual saat itu
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('penggunaan_spareparts');
    }
};
