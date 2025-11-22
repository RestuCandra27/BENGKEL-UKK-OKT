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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servis_id')->constrained('servis')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // Kasir yang menerbitkan
            $table->string('nomor_invoice', 50)->unique();
            $table->decimal('total_biaya_layanan', 15, 2)->default(0);
            $table->decimal('total_biaya_sparepart', 15, 2)->default(0);
            $table->decimal('total_tagihan', 15, 2)->default(0);
            $table->enum('status_pembayaran', ['Belum Lunas', 'Lunas'])->default('Belum Lunas');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
