<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan',
        ]);

        $otp = rand(100000, 999999);
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Kirim Email OTP
        try {
            \Illuminate\Support\Facades\Mail::raw("Halo {$user->nama},\n\nKode Verifikasi (OTP) Anda adalah: {$otp}\n\nKode ini berlaku selama 10 menit.", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Kode Verifikasi OTP - Candra Garage');
            });
        } catch (\Exception $e) {
            // Log error, but proceed
            \Illuminate\Support\Facades\Log::error('Gagal kirim email OTP: ' . $e->getMessage());
        }

        Auth::login($user);

        return redirect()->route('otp.verify');
    }
}
