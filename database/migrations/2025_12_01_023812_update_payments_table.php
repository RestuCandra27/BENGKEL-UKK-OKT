<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {

            if (!Schema::hasColumn('payments', 'pelanggan_id')) {
                $table->unsignedBigInteger('pelanggan_id')->nullable()->after('invoice_id');
            }

            if (!Schema::hasColumn('payments', 'bukti_path')) {
                $table->string('bukti_path')->nullable()->after('metode_bayar');
            }

            if (!Schema::hasColumn('payments', 'status')) {
                $table->enum('status', [
                    'pending',
                    'confirmed',
                    'rejected'
                ])->default('pending')->after('bukti_path');
            }

            if (!Schema::hasColumn('payments', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable()->after('catatan');
            }

            if (!Schema::hasColumn('payments', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('status');
            }

            if (!Schema::hasColumn('payments', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verified_by');
            }

        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->dropColumn([
                'pelanggan_id',
                'bukti_path',
                'status',
                'catatan_admin',
                'verified_by',
                'verified_at'
            ]);

        });
    }
};
