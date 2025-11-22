<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna (selain pelanggan).
     */
    public function index()
    {
        // Logika ini sudah bagus
        $users = User::where('role', '!=', 'pelanggan')->orderBy('nama', 'asc')->get();
        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * Menampilkan form untuk membuat user baru.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            // PERBAIKAN: Validasi ke tabel 'users'
            'email' => 'required|string|email|max:255|unique:users,email', 
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,montir,kasir',
            // Tambahkan validasi untuk data montir jika rolenya montir
            'kode_montir' => Rule::requiredIf($request->role == 'montir') . '|nullable|string|unique:users,kode_montir',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            
            // Simpan data spesifik role (sesuai migrasi 'users' kita yang baru)
            'kode_montir' => $request->role == 'montir' ? $request->kode_montir : null,
            'tanggal_bergabung' => $request->role == 'montir' ? now() : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit user.
     */
    public function edit(User $user) // Route-Model Binding ini sudah benar
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    /**
     * Mengupdate data user di database.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            // PERBAIKAN: Validasi ke tabel 'users' dan primary key 'id'
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => 'required|in:admin,montir,kasir',
            'password' => 'nullable|string|min:8|confirmed',
            // PERBAIKAN: Validasi unik saat update
            'kode_montir' => Rule::requiredIf($request->role == 'montir') . '|nullable|string|unique:users,kode_montir,' . $user->id,
        ]);

        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->role = $request->role;

        // Simpan data spesifik role
        $user->kode_montir = $request->role == 'montir' ? $request->kode_montir : null;
        
        // Logika password Anda sudah sempurna
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Menghapus data user dari database.
     */
    public function destroy(User $user)
    {
        // PERBAIKAN: Cek menggunakan primary key 'id'
        if (auth()->user()->id == $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil dihapus.');
    }
}