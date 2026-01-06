<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    public function index()
    {
        $logs = LogAktivitas::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.log_aktivitas.index', compact('logs'));
    }
}
