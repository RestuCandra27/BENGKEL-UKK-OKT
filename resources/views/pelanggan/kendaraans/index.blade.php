<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Kendaraan Saya') }}
            </h2>

            <a href="{{ route('pelanggan.kendaraans.create') }}" class="btn btn-primary">
                + Tambah Kendaraan
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mt-3">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No Polisi</th>
                        <th>Merek / Model</th>
                        <th>Tahun</th>
                        <th>Warna</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kendaraans as $k)
                        <tr>
                            <td>{{ $k->no_polisi }}</td>
                            <td>
                                {{ $k->merek ?? '-' }} {{ $k->model ?? '' }}<br>
                                <small class="text-muted">
                                    Rangka: {{ $k->nomor_rangka }}<br>
                                    Mesin: {{ $k->nomor_mesin }}
                                </small>
                            </td>
                            <td>{{ $k->tahun ?? '-' }}</td>
                            <td>{{ $k->warna ?? '-' }}</td>
                            <td>
                                <a href="{{ route('pelanggan.kendaraans.edit', $k->id) }}"
                                   class="btn btn-sm btn-info">
                                    Edit
                                </a>

                                <form action="{{ route('pelanggan.kendaraans.destroy', $k->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Hapus kendaraan ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                Belum ada kendaraan terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $kendaraans->links() }}
        </div>
    </div>
</x-app-layout>
