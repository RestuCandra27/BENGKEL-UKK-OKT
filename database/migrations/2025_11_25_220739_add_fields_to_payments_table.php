<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'invoice_id')) {
                $table->unsignedBigInteger('invoice_id')->after('id');
            }

            if (!Schema::hasColumn('payments', 'amount')) {
                $table->integer('amount')->after('invoice_id');
            }

            if (!Schema::hasColumn('payments', 'metode_bayar')) {
                $table->string('metode_bayar')->after('amount');
            }

            if (!Schema::hasColumn('payments', 'tanggal_bayar')) {
                $table->date('tanggal_bayar')->after('metode_bayar');
            }
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['invoice_id', 'amount', 'metode_bayar', 'tanggal_bayar']);
        });
    }
};
