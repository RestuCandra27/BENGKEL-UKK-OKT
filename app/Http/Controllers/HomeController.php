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

        if ($role == 'admin' || $role == 'montir' || $role == 'kasir') {
            // Jika user adalah admin, montir, atau kasir, arahkan ke dashboard admin
            return redirect()->route('admin.dashboard');
        } elseif ($role == 'pelanggan') {
            // Jika user adalah pelanggan, arahkan ke dashboard pelanggan
            return redirect()->route('pelanggan.dashboard');
        } else {
            // Sebagai cadangan jika ada peran lain, arahkan ke halaman utama
            return redirect('/');
        }
    }
}