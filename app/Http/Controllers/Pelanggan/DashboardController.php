<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk pelanggan.
     */
    public function index()
    {
        // Untuk saat ini, kita hanya menampilkan view saja.
        // Nanti kita bisa tambahkan logika untuk mengambil data riwayat servis, dll.
        return view('pelanggan.dashboard');
    }
}