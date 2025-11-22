{{-- Menggunakan layout utama 'app.blade.php'. --}}
<x-app-layout>
    {{-- Ini adalah header halaman yang sudah kita perbaiki --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            {{-- Ini adalah Judul Halaman Anda --}}
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Manajemen Pengguna') }}
            </h2>
            
            {{-- Tombol Tambah User yang sudah dipindah ke header --}}
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah User
            </a>
        </div>
    </x-slot>

    {{-- BLOK UNTUK MENAMPILKAN PESAN SUKSES ATAU ERROR --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Konten Card --}}
    <div class="card">

        {{-- PERBAIKAN: Seluruh <div class="card-header"> ... </div> telah dihapus --}}
        {{-- Ini membuat tabel langsung naik ke atas --}}

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-success">{{ $user->role }}</span></td>
                        <td>
                            {{-- Tombol Edit --}}
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info">Edit</a>
                            
                            {{-- Tombol Hapus (dengan class 'inline-block' yang sudah benar) --}}
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>