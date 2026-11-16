<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CekAdmin
{
    /**
     * Cek apakah user yang login punya role admin
     * Kalau bukan admin, redirect ke halaman user
     */
    public function handle(Request $request, Closure $next)
    {
        if (session('role') !== 'admin') {
            return redirect()->route('home')->with('error', 'Akses ditolak. Halaman khusus admin.');
        }

        return $next($request);
    }
}
