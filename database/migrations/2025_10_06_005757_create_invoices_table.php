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
        Schema::create('Invoice', function (Blueprint $table) {
            $table->id('id_invoice');
            $table->foreignId('id_servis')->unique()->constrained('Servis', 'id_servis');
            $table->string('nomor_invoice', 50)->unique();
            $table->date('tanggal_terbit');
            $table->decimal('grand_total', 15, 2);
            $table->enum('status', ['belum_dibayar', 'lunas', 'dibayar_sebagian'])->default('belum_dibayar');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
