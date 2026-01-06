<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * TAMPILKAN HALAMAN PROFIL (VIEW ONLY)
     */
    public function show(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * TAMPILKAN FORM EDIT PROFIL
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * SIMPAN PERUBAHAN PROFIL
     */
    public function update(Request $request)
{
    $user = $request->user();

    // 🔹 SAMAKAN dengan field di resources/views/profile/edit.blade.php
    $validated = $request->validate([
        'nama'        => ['required', 'string', 'max:255'],
        'email'       => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'no_hp'       => ['nullable', 'string', 'max:50'],
        'alamat'      => ['nullable', 'string', 'max:255'],

        // Field upload di form: name="foto_profil"
        'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
    ]);

    // 🔹 Simpan data teks
    $user->nama   = $validated['nama'];
    $user->email  = $validated['email'];
    $user->no_hp  = $validated['no_hp'] ?? null;
    $user->alamat = $validated['alamat'] ?? null;

    // 🔹 Handle upload foto (opsional)
    if ($request->hasFile('foto_profil')) {
        // Hapus foto lama kalau ada & filenya masih exist
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Simpan baru ke storage/app/public/profile-photos
        $path = $request->file('foto_profil')->store('profile-photos', 'public');

        // Pakai kolom bawaan Breeze: profile_photo_path
        $user->profile_photo_path = $path;
    }

    $user->save();

    return redirect()
        ->route('profile.show')
        ->with('success', 'Profil berhasil diperbarui.');
}


    /**
     * HAPUS AKUN (opsional, bawaan Breeze)
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
