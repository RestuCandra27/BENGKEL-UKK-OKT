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
        Schema::create('Riwayat_Kondisi', function (Blueprint $table) {
            $table->id('id_riwayat');
            $table->foreignId('id_servis')->constrained('Servis', 'id_servis')->onDelete('cascade');
            $table->text('catatan_saat_masuk')->nullable();
            $table->text('catatan_setelah_selesai')->nullable();
            $table->text('rekomendasi_montir')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_kondisis');
    }
};
