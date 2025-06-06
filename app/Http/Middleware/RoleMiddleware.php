<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Jika role user cocok dengan salah satu role yang diperbolehkan, lanjutkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika role tidak cocok, redirect berdasarkan role
        switch ($user->role) {
            case 'customer':
                return redirect()->route('home');
            case 'admin':
                return redirect()->route('dashboard');
            default:
                return redirect()->route('home'); // fallback jika tidak ada role cocok
        }
    }
}
