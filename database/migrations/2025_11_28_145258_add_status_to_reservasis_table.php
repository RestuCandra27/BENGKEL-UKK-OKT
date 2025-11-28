<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToReservasisTable extends Migration
{
    public function up()
    {
        Schema::table('reservasis', function (Blueprint $table) {
            // kolom status, untuk pending/disetujui/ditolak/dibatalkan
            $table->string('status', 20)->nullable()->after('keluhan');
        });
    }

    public function down()
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
