<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelian_spareparts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sparepart_id')
                ->constrained('spareparts')
                ->cascadeOnDelete();

            $table->date('tanggal_masuk');

            // jumlah stok yang masuk
            $table->unsignedInteger('jumlah_masuk');

            // hati-hati: stok tersisa JANGAN disimpan,
            // stok final ada di tabel spareparts

            // harga beli unit
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('harga_jual', 15, 2);

            // kolom catatan opsional
            $table->string('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian_spareparts');
    }
};
