<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import semua Controller yang dibutuhkan
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

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



// Controller Panel Kasir
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\InvoiceController as KasirInvoiceController;

// ⬇️ Tambahan untuk montir
use App\Http\Controllers\Montir\MontirDashboardController;
use App\Http\Controllers\Montir\MontirServisController;

// Controller Panel Pelanggan
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboardController;
use App\Http\Controllers\Pelanggan\ReservasiController;
use App\Http\Controllers\Pelanggan\InvoiceController as PelangganInvoiceController;
use App\Http\Controllers\Pelanggan\KendaraanController as PelangganKendaraanController;


// --- Rute Halaman Publik ---
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// --- Rute Pengatur Lalu Lintas (Setelah Login) ---
Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// === GRUP RUTE YANG DIPERBAIKI ===

// --- Grup Rute HANYA UNTUK ADMIN ---
Route::middleware(['auth', 'verified', 'check.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/users', UserController::class);
    Route::resource('/pelanggan', PelangganController::class);
    Route::resource('/layanans', LayananController::class);
    Route::resource('/paket-servis', PaketServisController::class);
    Route::resource('/spareparts', SparepartController::class);
    Route::resource('/kendaraans', KendaraanController::class);
    Route::resource('/pembelian-spareparts', PembelianSparepartController::class);
    Route::resource('/servis', ServisController::class);
    Route::post('/servis/{servis}/layanan', [\App\Http\Controllers\Admin\ServisController::class, 'storeLayanan'])->name('servis.layanan.store');
    Route::delete('/servis/{servis}/layanan/{layanan}', [\App\Http\Controllers\Admin\ServisController::class, 'destroyLayanan'])->name('servis.layanan.destroy');
    Route::post('/servis/{servis}/sparepart', [\App\Http\Controllers\Admin\ServisController::class, 'storeSparepart'])->name('servis.sparepart.store');
    Route::delete('/servis/{servis}/sparepart/{sparepart}', [\App\Http\Controllers\Admin\ServisController::class, 'destroySparepart'])->name('servis.sparepart.destroy');

    /* ==========================
     *  BOOKING / RESERVASI (ADMIN)
     * ========================== */
    Route::get('/reservasis', [AdminReservasiController::class, 'index'])
        ->name('reservasis.index');

    Route::get('/reservasis/{reservasi}', [AdminReservasiController::class, 'show'])
        ->name('reservasis.show');

    Route::post('/reservasis/{reservasi}/approve', [AdminReservasiController::class, 'approve'])
        ->name('reservasis.approve');

    Route::post('/reservasis/{reservasi}/reject', [AdminReservasiController::class, 'reject'])
        ->name('reservasis.reject');


    Route::resource('/invoices', InvoiceController::class)->only(['index', 'show', 'store']);

    // ⛔ TIDAK ADA lagi route payment di sini
});


// --- Grup Rute HANYA UNTUK MONTIR ---
Route::middleware(['auth', 'verified', 'check.role:montir'])
    ->prefix('montir')
    ->name('montir.')
    ->group(function () {

        // Dashboard montir
        Route::get('/dashboard', [MontirDashboardController::class, 'index'])
            ->name('dashboard');

        // Daftar servis milik montir
        Route::get('/servis', [MontirServisController::class, 'index'])
            ->name('servis.index');

        // Detail servis
        Route::get('/servis/{servis}', [MontirServisController::class, 'show'])
            ->name('servis.show');

        // Update status servis
        Route::post('/servis/{servis}/update-status',
            [MontirServisController::class, 'updateStatus']
        )->name('servis.update-status');

    });



// --- Grup Rute HANYA UNTUK KASIR ---
Route::middleware(['auth', 'verified', 'check.role:kasir'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {

        Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');
        Route::get('/invoices', [KasirInvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{invoice}', [KasirInvoiceController::class, 'show'])->name('invoices.show');

        // HANYA kasir yang punya route ini
        Route::post('/invoices/{invoice}/payments', [PaymentController::class, 'store'])
            ->name('invoices.payments.store');
    });



// --- Grup Rute HANYA UNTUK PELANGGAN ---
Route::middleware(['auth', 'verified', 'check.role:pelanggan'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {

        /* ==========================
         *  DASHBOARD
         * ========================== */
        Route::get('/dashboard', [PelangganDashboardController::class, 'index'])
            ->name('dashboard');


        /* ==========================
         *  RIWAYAT SERVIS
         * ========================== */
        Route::get('/servis', [\App\Http\Controllers\Pelanggan\ServisController::class, 'index'])
            ->name('servis.index');

        Route::get('/servis/{servis}', [\App\Http\Controllers\Pelanggan\ServisController::class, 'show'])
            ->name('servis.show');


        /* ==========================
         *  BOOKING / RESERVASI SERVIS
         * ========================== */

        // list reservasi
        Route::get('/reservasis', [ReservasiController::class, 'index'])
            ->name('reservasis.index');

        // form buat reservasi
        Route::get('/reservasis/create', [ReservasiController::class, 'create'])
            ->name('reservasis.create');

        // simpan reservasi
        Route::post('/reservasis', [ReservasiController::class, 'store'])
            ->name('reservasis.store');

        // detail reservasi
        Route::get('/reservasis/{reservasi}', [ReservasiController::class, 'show'])
            ->name('reservasis.show');

        // pembatalan reservasi
        Route::post('/reservasis/{reservasi}/cancel', [ReservasiController::class, 'cancel'])
            ->name('reservasis.cancel');


        /* ==========================
         *  INVOICE & PEMBAYARAN PELANGGAN
         * ========================== */

        Route::get('/invoices', [PelangganInvoiceController::class, 'index'])
            ->name('invoices.index');

        Route::get('/invoices/{invoice}', [PelangganInvoiceController::class, 'show'])
            ->name('invoices.show');


        /* ==========================
         *  KENDARAAN SAYA (CRUD)
         * ========================== */
        Route::resource('/kendaraans', PelangganKendaraanController::class)
            ->names('kendaraans')
            ->except(['show']);


        // (optional) kalau nanti ingin detail kendaraan pelanggan:
        // Route::get('/kendaraans/{kendaraan}', [PelangganKendaraanController::class, 'show'])
        //     ->name('kendaraans.show');
    });



// --- Rute Profil (Bisa diakses semua peran) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Rute Autentikasi ---
require __DIR__ . '/auth.php';
