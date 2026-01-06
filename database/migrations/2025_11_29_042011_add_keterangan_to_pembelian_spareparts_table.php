<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelian_spareparts', function (Blueprint $table) {
            $table->string('keterangan', 255)
                  ->nullable()
                  ->after('harga_jual'); // atau after('harga_beli') kalau tidak ada harga_jual
        });
    }

    public function down(): void
    {
        Schema::table('pembelian_spareparts', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });
    }
};
