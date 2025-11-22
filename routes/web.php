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
// (Anda mungkin perlu membuat controller ini nanti)
// use App\Http\Controllers\Admin\SparepartController; 
// use App\Http\Controllers\Admin\LayananController;

// Controller Panel Pelanggan
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboardController;
// (Anda mungkin perlu membuat controller ini nanti)
// use App\Http\Controllers\Pelanggan\ReservasiController;

// Controller Panel Staf (Montir & Kasir) - (Anda perlu membuatnya)
// use App\Http\Controllers\Montir\DashboardController as MontirDashboardController;
// use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;


// --- Rute Halaman Publik ---
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard'); // Jika sudah login, lempar ke pengatur lalu lintas
    }
    return view('welcome');
});

// --- Rute Pengatur Lalu Lintas (Setelah Login) ---
// Rute ini akan memutuskan dashboard mana yang akan ditampilkan
Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');


// === GRUP RUTE YANG DIPERBAIKI (LEBIH AMAN & RAPI) ===

// --- Grup Rute HANYA UNTUK ADMIN ---
// (Hanya Admin yang bisa mengakses /admin/*)
Route::middleware(['auth', 'verified', 'check.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/users', UserController::class);
    Route::resource('/pelanggan', PelangganController::class);
    Route::resource('/layanans', \App\Http\Controllers\Admin\LayananController::class);
    Route::resource('/paket-servis', \App\Http\Controllers\Admin\PaketServisController::class);
    Route::resource('/spareparts', \App\Http\Controllers\Admin\SparepartController::class);
    Route::resource('/kendaraans', \App\Http\Controllers\Admin\KendaraanController::class);
    Route::resource('/pembelian-spareparts', \App\Http\Controllers\Admin\PembelianSparepartController::class);
});

// --- Grup Rute HANYA UNTUK MONTIR ---
// (Hanya Montir yang bisa mengakses /montir/*)
Route::middleware(['auth', 'verified', 'check.role:montir'])->prefix('montir')->name('montir.')->group(function () {
    // (Anda perlu membuat MontirDashboardController)
    // Route::get('/dashboard', [MontirDashboardController::class, 'index'])->name('dashboard');

    // Nanti Anda akan menambahkan rute untuk Montir di sini:
    // Route::resource('/pekerjaan-servis', ServisMontirController::class);
});

// --- Grup Rute HANYA UNTUK KASIR ---
// (Hanya Kasir yang bisa mengakses /kasir/*)
Route::middleware(['auth', 'verified', 'check.role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    // (Anda perlu membuat KasirDashboardController)
    // Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');

    // Nanti Anda akan menambahkan rute untuk Kasir di sini:
    // Route::resource('/transaksi', TransaksiController::class);
});

// --- Grup Rute HANYA UNTUK PELANGGAN ---
// (Hanya Pelanggan yang bisa mengakses /pelanggan/*)
Route::middleware(['auth', 'verified', 'check.role:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');

    // Nanti Anda akan menambahkan rute untuk Pelanggan di sini:
    // Route::resource('/reservasi-saya', ReservasiController::class);
});


// --- Rute Profil (Bisa diakses semua peran) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- Rute Autentikasi ---
require __DIR__ . '/auth.php';
