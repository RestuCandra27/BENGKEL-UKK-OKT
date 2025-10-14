<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    /**
     * Menampilkan halaman daftar pelanggan.
     */
    public function index()
    {
        // Ambil semua data pelanggan beserta data user terkait
         $pelanggans = Pelanggan::with('user')->paginate(10); 

        return view('admin.pelanggan.index', compact('pelanggans'));
    }

    /**
     * Menampilkan form untuk membuat pelanggan baru.
     */
    public function create()
    {
        return view('admin.pelanggan.create');
    }

    /**
     * Menyimpan data pelanggan baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:User,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'jenis_member' => ['required', 'in:Reguler,VIP,Fleet'],
        ]);

        // 2. Gunakan Database Transaction untuk keamanan
        DB::beginTransaction();
        try {
            // Buat data di tabel User terlebih dahulu
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pelanggan', // Otomatis set role sebagai pelanggan
            ]);

            // Kemudian, buat data di tabel Pelanggan menggunakan ID dari user baru
            Pelanggan::create([
                'id_user' => $user->id_user,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'jenis_member' => $request->jenis_member,
            ]);

            DB::commit(); // Jika semua berhasil, simpan perubahan

        } catch (\Exception $e) {
            DB::rollBack(); // Jika ada error, batalkan semua proses
            // Kembali ke halaman form dengan pesan error umum
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.'])->withInput();
        }
        
        // 3. Redirect ke halaman daftar pelanggan dengan pesan sukses
        return redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan baru berhasil ditambahkan.');
    }
}

