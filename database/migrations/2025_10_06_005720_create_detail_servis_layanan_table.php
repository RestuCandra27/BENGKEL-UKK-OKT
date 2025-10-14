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
        Schema::create('Detail_Servis_Layanan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_servis')->constrained('Servis', 'id_servis')->onDelete('cascade');
            $table->foreignId('id_layanan')->nullable()->constrained('Layanan', 'id_layanan');
            $table->foreignId('id_paket')->nullable()->constrained('Paket_Servis', 'id_paket');
            $table->decimal('biaya_aktual', 15, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_servis_layanan');
    }
};
