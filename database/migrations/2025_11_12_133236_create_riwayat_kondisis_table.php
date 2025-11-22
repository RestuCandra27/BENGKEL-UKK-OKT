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
        Schema::create('riwayat_kondisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servis_id')->constrained('servis')->onDelete('cascade');
            $table->text('catatan_saat_masuk')->nullable();
            $table->text('catatan_setelah_selesai')->nullable();
            $table->text('rekomendasi_montir')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('riwayat_kondisis');
    }
};
