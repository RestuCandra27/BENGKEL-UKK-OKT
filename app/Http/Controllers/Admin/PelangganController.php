<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// PERBAIKAN: Kita HANYA butuh Model User.
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    /**
     * Menampilkan halaman daftar pelanggan.
     * Logika ini JAUH LEBIH SEDERHANA.
     */
    public function index()
    {
        // PERBAIKAN: Cukup ambil dari tabel 'users' dimana role='pelanggan'
        $pelanggans = User::where('role', 'pelanggan')
                           ->orderBy('nama', 'asc')
                           ->paginate(10); 

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
     * Logika ini JAUH LEBIH SEDERHANA.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            // PERBAIKAN: Validasi ke tabel 'users'
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'], 
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'jenis_member' => ['required', 'in:Reguler,VIP,Fleet'],
        ]);

        // 2. Simpan ke SATU TABEL. Tidak perlu Transaction.
        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan', // Otomatis set role
            
            // Simpan data profil langsung ke tabel 'users'
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'jenis_member' => $request->jenis_member,
            // (Anda bisa tambahkan kolom lain dari migrasi 'users' kita di sini)
        ]);
        
        // 3. Redirect
        return redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan baru berhasil ditambahkan.');
    }

    /**
     * (Fungsi Baru) Menampilkan form untuk mengedit pelanggan.
     * Kita menggunakan Route-Model Binding. 'User $pelanggan' akan error
     * karena nama parameternya 'pelanggan'. Kita perbaiki di bawah.
     */
    public function edit($id) // Terima $id dari rute
    {
        $pelanggan = User::where('id', $id)->where('role', 'pelanggan')->firstOrFail();
        return view('admin.pelanggan.edit', compact('pelanggan'));
    }

    /**
     * (Fungsi Baru) Mengupdate data pelanggan di database.
     */
    public function update(Request $request, $id) // Terima $id dari rute
    {
        $pelanggan = User::where('id', $id)->where('role', 'pelanggan')->firstOrFail();

        // 1. Validasi input
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            // PERBAIKAN: Validasi unik dengan 'ignore' ke primary key 'id'
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($pelanggan->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // Password boleh kosong saat update
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'jenis_member' => ['required', 'in:Reguler,VIP,Fleet'],
        ]);

        // 2. Update data
        $pelanggan->nama = $request->nama;
        $pelanggan->email = $request->email;
        $pelanggan->no_hp = $request->no_hp;
        $pelanggan->alamat = $request->alamat;
        $pelanggan->jenis_member = $request->jenis_member;

        // Logika update password (jika diisi)
        if ($request->filled('password')) {
            $pelanggan->password = Hash::make($request->password);
        }
        
        $pelanggan->save();
        
        // 3. Redirect
        return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    /**
     * (Fungsi Baru) Menghapus data pelanggan dari database.
     */
    public function destroy($id) // Terima $id dari rute
    {
        $pelanggan = User::where('id', $id)->where('role', 'pelanggan')->firstOrFail();
        
        // Tambahan keamanan: jangan hapus pelanggan jika masih punya data servis
        // if ($pelanggan->servis()->count() > 0) {
        //     return back()->with('error', 'Tidak bisa menghapus pelanggan yang masih memiliki riwayat servis.');
        // }

        $pelanggan->delete();

        return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan berhasil dihapus.');
    }
}