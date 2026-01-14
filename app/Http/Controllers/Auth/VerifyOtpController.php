<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VerifyOtpController extends Controller
{
    /**
     * Display the OTP verification view.
     */
    public function create(): View
    {
        return view('auth.verify-otp');
    }

    /**
     * Handle an incoming OTP verification request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6'],
        ]);

        $user = Auth::user();

        // 1. Cek apakah OTP cocok
        if ($request->otp_code !== $user->otp_code) {
            throw ValidationException::withMessages([
                'otp_code' => ['Kode OTP salah. Silakan cek email Anda kembali.'],
            ]);
        }

        // 2. Cek apakah kedaluwarsa (opsional, tapi disarankan)
        if ($user->otp_expires_at && now()->gt($user->otp_expires_at)) {
            throw ValidationException::withMessages([
                'otp_code' => ['Kode OTP sudah kedaluwarsa. Silakan minta kode baru.'],
            ]);
        }

        // 3. Verifikasi Berhasil
        $user->forceFill([
            'email_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ])->save();

        return redirect()->intended(route('dashboard', absolute: false))
            ->with('success', 'Email berhasil diverifikasi! Selamat datang.');
    }
}
