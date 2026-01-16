<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('status', ['confirmed', 'pending', 'rejected'])
                ->default('confirmed')
                ->after('catatan');

            $table->date('tanggal_bayar')->nullable()->after('status');
            $table->foreignId('pelanggan_id')->nullable()->after('invoice_id');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'tanggal_bayar',
                'pelanggan_id',
                'catatan_admin',
                'verified_by',
                'verified_at',
            ]);
        });
    }
};

