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
        Schema::create('Detail_Paket_Layanan', function (Blueprint $table) {
            $table->foreignId('id_paket')->constrained('Paket_Servis', 'id_paket')->onDelete('cascade');
            $table->foreignId('id_layanan')->constrained('Layanan', 'id_layanan')->onDelete('cascade');

            // Membuat primary key gabungan
            $table->primary(['id_paket', 'id_layanan']);
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_paket_layanan');
    }
};
