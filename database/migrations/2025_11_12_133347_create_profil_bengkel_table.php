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
        // Tabel ini hanya akan punya 1 baris data
        Schema::create('profil_bengkel', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bengkel', 100);
            $table->text('alamat');
            $table->string('no_telepon', 20);
            $table->string('email_bengkel', 100);
            $table->string('website')->nullable();
            $table->text('jam_operasional')->nullable();
            // $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('profil_bengkel');
    }
};
