<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('servis', 'reservasi_id')) {
        Schema::table('servis', function (Blueprint $table) {
            $table->unsignedBigInteger('reservasi_id')
                  ->nullable()
                  ->after('id');

            $table->foreign('reservasi_id')
                  ->references('id')->on('reservasis')
                  ->onDelete('set null');       
        });
    }
    }

    public function down(): void
    {
        if (Schema::hasColumn('servis', 'reservasi_id')) {
        Schema::table('servis', function (Blueprint $table) {
            $table->dropForeign(['reservasi_id']);
            $table->dropColumn('reservasi_id');
        });
    }
    }
};
