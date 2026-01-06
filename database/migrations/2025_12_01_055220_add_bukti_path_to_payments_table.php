<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // sesuaikan nama kolom yang sudah ada di tabelmu
            if (!Schema::hasColumn('payments', 'bukti_path')) {
                $table->string('bukti_path')->nullable()->after('jumlah_bayar');
            }
            if (!Schema::hasColumn('payments', 'catatan')) {
                $table->text('catatan')->nullable()->after('bukti_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'bukti_path')) {
                $table->dropColumn('bukti_path');
            }
            if (Schema::hasColumn('payments', 'catatan')) {
                $table->dropColumn('catatan');
            }
        });
    }
};
