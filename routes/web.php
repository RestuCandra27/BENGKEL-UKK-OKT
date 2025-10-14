<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import semua Controller yang dibutuhkan
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\ProfileController;

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


// --- Grup Rute untuk Panel Admin ---
Route::middleware(['auth', 'verified', 'check.role:admin,montir,kasir'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/users', UserController::class);
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
    Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
});

// --- Grup Rute untuk Panel Pelanggan ---
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
require __DIR__.'/auth.php';

