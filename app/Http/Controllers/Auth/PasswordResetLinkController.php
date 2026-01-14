<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Jika user tidak ditemukan, kembali dengan error
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => [trans('passwords.user')],
            ]);
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10), // OTP berlaku 10 menit
        ]);

        // Kirim Email OTP
        try {
            Mail::raw("Halo {$user->nama},\n\nKode OTP untuk Reset Password Anda adalah: {$otp}\n\nJangan berikan kode ini kepada siapapun.", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Kode OTP Reset Password - Candra Garage');
            });
        } catch (\Exception $e) {
             throw ValidationException::withMessages([
                'email' => ['Gagal mengirim email: ' . $e->getMessage()],
            ]);
        }

        // Redirect ke halaman input OTP & Password baru
        return redirect()->route('password.reset', ['email' => $request->email])
            ->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }
}
