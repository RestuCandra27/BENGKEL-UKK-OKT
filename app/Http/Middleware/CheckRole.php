<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login dan rolenya ada di dalam daftar yang diizinkan
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            // Jika tidak, usir dia (tampilkan halaman error 403 Forbidden)
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.');
        }

        // Jika rolenya cocok, izinkan dia masuk
        return $next($request);
    }
}
