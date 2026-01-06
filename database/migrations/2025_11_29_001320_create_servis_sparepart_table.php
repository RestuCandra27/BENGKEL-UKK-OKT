<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servis_sparepart', function (Blueprint $table) {
            $table->id();

            $table->foreignId('servis_id')
                ->constrained('servis')   // sesuaikan kalau tabelnya 'services'
                ->cascadeOnDelete();

            $table->foreignId('sparepart_id')
                ->constrained('spareparts')
                ->cascadeOnDelete();

            $table->unsignedInteger('jumlah');
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);

            $table->timestamps();

            // optional: cegah duplikat sparepart di servis yang sama
            $table->unique(['servis_id', 'sparepart_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servis_sparepart');
    }
};
