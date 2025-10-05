<?php

namespace App\Http\Middleware;

// use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        foreach ($guards as $guard) {
           if (Auth::guard($guard)->check()) {
                if (Auth::user()->role !== '3') {
                    return redirect('/home');
                }
            }
        }

        return $next($request);
    }
}
