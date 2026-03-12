<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN role-nya adalah 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Lanjutkan perjalanan (Boleh akses)
        }

        // Jika bukan admin, tendang kembali ke dashboard dengan pesan error
        return redirect()->route('dashboard')->with('error', 'Akses ditolak: Halaman ini khusus untuk Admin.');
    }
}