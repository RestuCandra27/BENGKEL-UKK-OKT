<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Siapa yg melakukan
            $table->string('aktivitas'); // Misal: "Membuat invoice INV-123"
            $table->string('model_terkait')->nullable(); // Misal: 'App\Models\Invoice'
            $table->unsignedBigInteger('model_id_terkait')->nullable();
            $table->text('detail_perubahan')->nullable(); // JSON data lama vs data baru
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
