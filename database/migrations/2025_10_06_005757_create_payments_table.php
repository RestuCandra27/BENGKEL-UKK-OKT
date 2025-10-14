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
        Schema::create('Payment', function (Blueprint $table) {
            $table->id('id_payment');
            $table->foreignId('id_invoice')->constrained('Invoice', 'id_invoice');
            $table->foreignId('id_kasir')->constrained('User', 'id_user');
            $table->dateTime('tanggal_pembayaran');
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'qris']);
            $table->decimal('jumlah_bayar', 15, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
