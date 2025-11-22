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
        // KONVENSI: Nama tabel 'kendaraans'
        Schema::create('kendaraans', function (Blueprint $table) {
            // KONVENSI: Primary key 'id'
            $table->id(); 

            // KONVENSI: Foreign key ke tabel 'users'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('merek', 50)->nullable();
            $table->string('model', 50)->nullable();
            $table->string('no_polisi', 20)->unique();
            $table->year('tahun')->nullable();
            $table->string('warna', 30)->nullable();
            $table->string('nomor_rangka', 50)->unique();
            $table->string('nomor_mesin', 50)->unique();
            
            // KONVENSI: Timestamps
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
