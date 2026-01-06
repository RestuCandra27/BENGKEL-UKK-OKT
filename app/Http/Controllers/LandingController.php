<?php

namespace App\Http\Controllers;

use App\Models\ProfilBengkel;
use App\Models\Layanan;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    /**
     * Halaman landing publik (profil bengkel).
     * Guest: lihat landing
     * User login: diarahkan ke dashboard internal.
     */
    public function index()
    {
        // Kalau user sudah login → langsung ke dashboard seperti biasa
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Ambil 1 profil bengkel (anggap cuma 1 row)
        $profil = ProfilBengkel::first();

        // Ambil beberapa layanan utama (maks 4, biar rapi)
        $layanans = Layanan::orderBy('nama_layanan', 'asc')
            ->take(4)
            ->get();

        return view('landing', compact('profil', 'layanans'));
    }
}
