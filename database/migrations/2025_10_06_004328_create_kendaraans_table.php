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
        Schema::create('Kendaraan', function (Blueprint $table) {
            $table->id('id_kendaraan');
            $table->foreignId('id_user')->constrained('User', 'id_user')->onDelete('cascade');

            $table->string('merek', 50)->nullable();
            $table->string('model', 50)->nullable();
            $table->string('no_polisi', 20)->unique();
            $table->year('tahun')->nullable();
            $table->string('warna', 30)->nullable();
            $table->string('nomor_rangka', 50)->unique();
            $table->string('nomor_mesin', 50)->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
