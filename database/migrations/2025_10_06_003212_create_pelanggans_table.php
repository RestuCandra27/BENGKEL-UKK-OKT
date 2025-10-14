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
        Schema::create('Pelanggan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->primary(); 
            $table->foreign('id_user')->references('id_user')->on('User')->onDelete('cascade');
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Pria', 'Wanita'])->nullable();
            $table->string('pekerjaan', 50)->nullable();
            $table->enum('jenis_member', ['Reguler', 'VIP', 'Fleet'])->default('Reguler');
            $table->integer('poin_loyalitas')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
