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
        Schema::create('detail_servis_layanans', function (Blueprint $table) {
            $table->id(); // Kunci utama untuk tabel ini
            $table->foreignId('servis_id')->constrained('servis')->onDelete('cascade');
            $table->foreignId('layanan_id')->constrained('layanans')->onDelete('cascade');
            // $table->integer('jumlah')->default(1); // Opsional
            // $table->decimal('biaya', 15, 2); // Opsional
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('detail_servis_layanans');
    }
};
