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


// Controller Panel Kasir
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\InvoiceController as KasirInvoiceController;


// Controller Panel Pelanggan
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboardController;

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

    Route::resource('/invoices', InvoiceController::class)->only(['index', 'show', 'store']);

    // ⛔ TIDAK ADA lagi route payment di sini
});


// --- Grup Rute HANYA UNTUK MONTIR ---
Route::middleware(['auth', 'verified', 'check.role:montir'])->prefix('montir')->name('montir.')->group(function () {
    // Route montir akan ditambahkan nanti
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
Route::middleware(['auth', 'verified', 'check.role:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');
});

// --- Rute Profil (Bisa diakses semua peran) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Rute Autentikasi ---
require __DIR__ . '/auth.php';
