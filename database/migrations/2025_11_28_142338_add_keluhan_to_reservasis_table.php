<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeluhanToReservasisTable extends Migration
{
    public function up()
    {
        Schema::table('reservasis', function (Blueprint $table) {
            // kolom keluhan, bisa text karena biasanya agak panjang
            $table->text('keluhan')->nullable()->after('jam_booking');
        });
    }

    public function down()
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->dropColumn('keluhan');
        });
    }
}
