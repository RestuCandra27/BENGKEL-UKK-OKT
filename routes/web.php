<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import semua Controller yang dibutuhkan
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;


// Controller Panel Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\PaketServisController;
use App\Http\Controllers\Admin\SparepartController;
use App\Http\Controllers\Admin\KendaraanController;
use App\Http\Controllers\Admin\PembelianSparepartController;
use App\Http\Controllers\Admin\ServisController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReservasiController as AdminReservasiController;
use App\Http\Controllers\Admin\LogAktivitasController;

// Controller Panel Kasir
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\InvoiceController as KasirInvoiceController;

// Controller Panel Montir
use App\Http\Controllers\Montir\MontirDashboardController;
use App\Http\Controllers\Montir\MontirServisController;

// Controller Panel Pelanggan
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboardController;
use App\Http\Controllers\Pelanggan\ReservasiController;
use App\Http\Controllers\Pelanggan\InvoiceController as PelangganInvoiceController;
use App\Http\Controllers\Pelanggan\KendaraanController as PelangganKendaraanController;
use App\Http\Controllers\Pelanggan\PaymentController as PelangganPaymentController;


// --- Rute Halaman Publik (Landing Page Bengkel) ---
Route::get('/', [LandingController::class, 'index'])
    ->name('landing');

// --- Rute Pengatur Lalu Lintas (Setelah Login) ---
Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// --- GRUP RUTE HANYA UNTUK ADMIN ---
Route::middleware(['auth', 'verified', 'check.role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // =========================
        // MANAJEMEN USER & DATA MASTER
        // =========================
        Route::resource('/users', UserController::class);
        Route::resource('/pelanggan', PelangganController::class);
        Route::resource('/layanans', LayananController::class);

        // (Masih ada, nanti di tahap lain akan dihapus)
        Route::resource('/paket-servis', PaketServisController::class);

        // SPAREPART
        Route::resource('/spareparts', SparepartController::class);

        // STOK MASUK
        Route::get('/stok-masuk', [PembelianSparepartController::class, 'index'])
            ->name('stok-masuk.index');
        Route::get('/stok-masuk/create', [PembelianSparepartController::class, 'create'])
            ->name('stok-masuk.create');
        Route::post('/stok-masuk', [PembelianSparepartController::class, 'store'])
            ->name('stok-masuk.store');
        Route::delete('/stok-masuk/{pembelianSparepart}', [PembelianSparepartController::class, 'destroy'])
            ->name('stok-masuk.destroy');

        // KENDARAAN
        Route::resource('/kendaraans', KendaraanController::class);

        // =========================
        // SERVIS
        // =========================
        Route::resource('/servis', ServisController::class);
        Route::post('/servis/{servis}/layanan', [ServisController::class, 'storeLayanan'])
            ->name('servis.layanan.store');
        Route::delete('/servis/{servis}/layanan/{layanan}', [ServisController::class, 'destroyLayanan'])
            ->name('servis.layanan.destroy');
        Route::post('/servis/{servis}/sparepart', [ServisController::class, 'storeSparepart'])
            ->name('servis.sparepart.store');
        Route::delete('/servis/{servis}/sparepart/{sparepart}', [ServisController::class, 'destroySparepart'])
            ->name('servis.sparepart.destroy');

        // =========================
        // BOOKING / RESERVASI
        // =========================
        Route::get('/reservasis', [AdminReservasiController::class, 'index'])
            ->name('reservasis.index');
        Route::get('/reservasis/{reservasi}', [AdminReservasiController::class, 'show'])
            ->name('reservasis.show');
        Route::post('/reservasis/{reservasi}/approve', [AdminReservasiController::class, 'approve'])
            ->name('reservasis.approve');
        Route::post('/reservasis/{reservasi}/reject', [AdminReservasiController::class, 'reject'])
            ->name('reservasis.reject');

        // =========================
        // INVOICE & PEMBAYARAN (ADMIN ONLY - OPSI A)
        // =========================

        // Invoice
        Route::resource('/invoices', InvoiceController::class)
            ->only(['index', 'show', 'store']);

        // Cetak nota
        Route::get('/invoices/{invoice}/nota', [InvoiceController::class, 'nota'])
            ->name('invoices.nota');

        // ADMIN INPUT PEMBAYARAN (LANGSUNG LUNAS)
        Route::post('/invoices/{invoice}/payments', [PaymentController::class, 'store'])
            ->name('invoices.payments.store');

        // Riwayat pembayaran
        Route::get('/payments', [PaymentController::class, 'index'])
            ->name('payments.index');
        Route::get('/payments/{payment}', [PaymentController::class, 'show'])
            ->name('payments.show');

        // =========================
        // LOG AKTIVITAS
        // =========================
        Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])
            ->name('log-aktivitas.index');

    });


// --- Grup Rute HANYA UNTUK MONTIR ---
Route::middleware(['auth', 'verified', 'check.role:montir'])
    ->prefix('montir')
    ->name('montir.')
    ->group(function () {

        Route::get('/dashboard', [MontirDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/servis', [MontirServisController::class, 'index'])
            ->name('servis.index');

        Route::get('/servis/{servis}', [MontirServisController::class, 'show'])
            ->name('servis.show');

        Route::post('/servis/{servis}/update-status', [MontirServisController::class, 'updateStatus'])
            ->name('servis.update-status');

        Route::post('servis/{servis}/riwayat', [MontirServisController::class, 'updateRiwayat'])
            ->name('servis.update-riwayat');

        Route::post('/servis/{servis}/layanan', [MontirServisController::class, 'storeLayanan'])
            ->name('servis.layanan.store');
        Route::delete('/servis/{servis}/layanan/{layanan}', [MontirServisController::class, 'destroyLayanan'])
            ->name('servis.layanan.destroy');

        // Montir tambah / hapus SPAREPART
        Route::post('/servis/{servis}/sparepart', [MontirServisController::class, 'storeSparepart'])
            ->name('servis.sparepart.store');
        Route::delete('/servis/{servis}/sparepart/{sparepart}', [MontirServisController::class, 'destroySparepart'])
            ->name('servis.sparepart.destroy');
    });

// --- Grup Rute HANYA UNTUK KASIR ---
// Route::middleware(['auth', 'verified', 'check.role:kasir'])
//     ->prefix('kasir')
//     ->name('kasir.')
//     ->group(function () {

//         Route::get('/dashboard', [KasirDashboardController::class, 'index'])
//             ->name('dashboard');

//         Route::get('/invoices', [KasirInvoiceController::class, 'index'])
//             ->name('invoices.index');
//         Route::get('/invoices/{invoice}', [KasirInvoiceController::class, 'show'])
//             ->name('invoices.show');

//         // Hanya kasir yang boleh input pembayaran
//         Route::post('/invoices/{invoice}/payments', [PaymentController::class, 'store'])
//             ->name('invoices.payments.store');
//     });

// --- Grup Rute HANYA UNTUK PELANGGAN ---
Route::middleware(['auth', 'verified', 'check.role:pelanggan'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {

        Route::get('/dashboard', [PelangganDashboardController::class, 'index'])
            ->name('dashboard');

        // Riwayat servis
        Route::get('/servis', [\App\Http\Controllers\Pelanggan\ServisController::class, 'index'])
            ->name('servis.index');
        Route::get('/servis/{servis}', [\App\Http\Controllers\Pelanggan\ServisController::class, 'show'])
            ->name('servis.show');

        // Reservasi
        Route::get('/reservasis', [ReservasiController::class, 'index'])
            ->name('reservasis.index');
        Route::get('/reservasis/create', [ReservasiController::class, 'create'])
            ->name('reservasis.create');
        Route::post('/reservasis', [ReservasiController::class, 'store'])
            ->name('reservasis.store');
        Route::get('/reservasis/{reservasi}', [ReservasiController::class, 'show'])
            ->name('reservasis.show');
        Route::post('/reservasis/{reservasi}/cancel', [ReservasiController::class, 'cancel'])
            ->name('reservasis.cancel');

                // Invoice pelanggan
        Route::get('/invoices', [PelangganInvoiceController::class, 'index'])
            ->name('invoices.index');
        Route::get('/invoices/{invoice}', [PelangganInvoiceController::class, 'show'])
            ->name('invoices.show');
                // Pelanggan kirim pembayaran mandiri
        Route::post('/invoices/{invoice}/payments', [PaymentController::class, 'storeFromCustomer'])
            ->name('invoices.payments.store');


        // 💰 Pelanggan kirim pembayaran
        Route::post('/invoices/{invoice}/payments', [PelangganPaymentController::class, 'store'])
            ->name('invoices.payments.store');


        // Kendaraan saya
        Route::resource('/kendaraans', PelangganKendaraanController::class)
            ->names('kendaraans')
            ->except(['show']);
    });

// --- Rute Profil (Bisa diakses semua peran) ---
// --- Rute Profil (Bisa diakses semua peran) ---
Route::middleware('auth')->group(function () {
    // HALAMAN LIHAT PROFIL
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    // HALAMAN EDIT PROFIL
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // SIMPAN PERUBAHAN PROFIL
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // HAPUS AKUN (kalau dipakai)
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- Rute Autentikasi ---
require __DIR__ . '/auth.php';
