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
        Schema::create('Profil_Bengkel', function (Blueprint $table) {
            $table->id('id_bengkel');
            $table->string('nama_bengkel', 100);
            $table->text('alamat_bengkel');
            // ... Tambahkan kolom profil bengkel lainnya sesuai skrip SQL final ...
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_bengkel');
    }
};
