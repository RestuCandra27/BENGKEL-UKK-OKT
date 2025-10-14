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
        Schema::create('Penggunaan_Sparepart', function (Blueprint $table) {
            $table->foreignId('id_servis')->constrained('Servis', 'id_servis')->onDelete('cascade');
            $table->foreignId('id_pembelian')->constrained('Pembelian_Sparepart', 'id_pembelian');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->primary(['id_servis', 'id_pembelian']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunaan_sparepart');
    }
};
