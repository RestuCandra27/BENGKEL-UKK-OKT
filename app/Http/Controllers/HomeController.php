<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Mengarahkan pengguna ke dashboard yang sesuai berdasarkan peran mereka.
     */
    public function index()
    {
        $role = Auth::user()->role;

        // PERBAIKAN: Pisahkan setiap role ke dashboard-nya sendiri
        
        if ($role == 'admin') {
            // Admin ke admin.dashboard
            return redirect()->route('admin.dashboard');
        
        } elseif ($role == 'montir') {
            // Montir ke montir.dashboard
            // (Pastikan Anda sudah membuat rute ini di web.php)
            return redirect()->route('montir.dashboard'); // INI DIPERBAIKI

        } elseif ($role == 'kasir') {
            // Kasir ke kasir.dashboard
            // (Pastikan Anda sudah membuat rute ini di web.php)
            return redirect()->route('kasir.dashboard'); // INI DIPERBAIKI
        
        } elseif ($role == 'pelanggan') {
            // Pelanggan ke pelanggan.dashboard
            return redirect()->route('pelanggan.dashboard');
        
        } else {
            // Fallback
            return redirect('/');
        }
    }
}