<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // Menggunakan ...$roles untuk menangkap semua parameter role yang diberikan
    public function handle(Request $request, Closure $next, ...$roles): Response 
    {
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Ambil role user (diambil langsung dari kolom 'role' di tabel users)
        $userRole = Auth::user()->role; 

        // 3. Cocokkan role user dengan daftar roles yang diizinkan
        // Jika role user TIDAK ADA di dalam daftar $roles, maka tolak akses.
        if (!in_array($userRole, $roles)) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
