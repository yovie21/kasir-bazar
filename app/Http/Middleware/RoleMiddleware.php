<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Ambil role user (misal role_id = 3 = admin)
        $userRole = auth()->user()->role->name ?? null;

        // Cocokkan role dengan parameter dari route
        if ($userRole !== $role) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
