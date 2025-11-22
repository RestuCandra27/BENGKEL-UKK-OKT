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
        // KONVENSI: Nama tabel 'users' (lowercase, plural)
        Schema::create('users', function (Blueprint $table) {
            
            // KONVENSI: Primary key 'id'
            $table->id(); 
            
            // --- KOLOM LOGIN UTAMA (Untuk semua role) ---
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'montir', 'pelanggan', 'kasir']);
            $table->timestamp('email_verified_at')->nullable(); // Standar Laravel
            $table->rememberToken(); // Standar Laravel
            
            // --- KOLOM PROFIL UMUM (bisa diisi oleh semua role, dibuat nullable) ---
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Pria', 'Wanita'])->nullable();
            
            // --- KOLOM KHUSUS PELANGGAN (nullable) ---
            $table->string('pekerjaan', 50)->nullable();
            $table->enum('jenis_member', ['Reguler', 'VIP', 'Fleet'])->nullable()->default('Reguler');
            $table->integer('poin_loyalitas')->nullable()->default(0);
            
            // --- KOLOM KHUSUS MONTIR (nullable) ---
            $table->string('kode_montir', 10)->unique()->nullable();
            $table->string('spesialisasi', 50)->nullable();
            $table->enum('level', ['Junior', 'Senior', 'Kepala Montir'])->nullable();
            $table->date('tanggal_bergabung')->nullable();
            $table->string('kontak_darurat_nama', 100)->nullable();
            $table->string('kontak_darurat_nomor', 20)->nullable();
            
            // KONVENSI: 'created_at' dan 'updated_at'
            $table->timestamps(); 
        });

        // Tabel-tabel standar ini sekarang akan berfungsi
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            // KONVENSI: foreignId 'user_id' akan otomatis terhubung ke 'id' di tabel 'users'
            $table->foreignId('user_id')->nullable()->index()->constrained('users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};