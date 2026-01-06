<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                Log Aktivitas
            </h2>
        </div>
    </x-slot>

    <div class="card mt-3">
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Model</th>
                        <th>ID</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $loop->index }}</td>
                            <td>{{ $log->created_at->format('d-m-Y H:i') }}</td>
                            <td>{{ $log->user->nama ?? '-' }}</td>
                            <td>{{ $log->aksi }}</td>
                            <td>{{ $log->model ?? '-' }}</td>
                            <td>{{ $log->model_id ?? '-' }}</td>
                            <td>{{ $log->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada log aktivitas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="card-footer clearfix">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
