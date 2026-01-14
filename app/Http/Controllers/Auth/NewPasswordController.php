<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp_code' => ['required', 'string', 'size:6'], // Validasi OTP
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
             throw ValidationException::withMessages([
                'email' => [trans('passwords.user')],
            ]);
        }

        // Cek Kesesuaian OTP
        if ($user->otp_code !== $request->otp_code) {
             throw ValidationException::withMessages([
                'otp_code' => ['Kode OTP salah atau tidak valid.'],
            ]);
        }

        // Cek Kedaluwarsa
        if ($user->otp_expires_at && now()->gt($user->otp_expires_at)) {
             throw ValidationException::withMessages([
                'otp_code' => ['Kode OTP sudah kedaluwarsa.'],
            ]);
        }

        // Reset Password
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
            'otp_code' => null,     // Hapus OTP setelah dipakai
            'otp_expires_at' => null
        ])->save();

        event(new PasswordReset($user));

        return redirect()->route('login')->with('status', 'Password berhasil diubah, silakan login dengan password baru.');
    }
}
