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
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id();

            $table->string('kode_sku', 50)->unique()->nullable();
            $table->string('nama_sparepart', 100);
            $table->string('merek', 50)->nullable();
            $table->string('kategori', 50)->nullable();

            // ====== TAMBAHAN UNTUK MODUL STOK ======
            $table->unsignedInteger('stok')->default(0);
            $table->decimal('harga_jual', 15, 2)->nullable();
            // ======================================

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spareparts');
    }
};
