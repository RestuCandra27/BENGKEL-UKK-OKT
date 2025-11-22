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
        Schema::create('detail_paket_layanan', function (Blueprint $table) {
            // KONVENSI: Foreign key ke 'paket_servis'
            $table->foreignId('paket_servis_id')->constrained('paket_servis')->onDelete('cascade');
            
            // KONVENSI: Foreign key ke 'layanans'
            $table->foreignId('layanan_id')->constrained('layanans')->onDelete('cascade');

            // Primary key gabungan
            $table->primary(['paket_servis_id', 'layanan_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('detail_paket_layanan');
    }
};
