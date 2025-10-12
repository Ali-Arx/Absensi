<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        // Jika user belum login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Jika role cocok
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika role tidak cocok, arahkan balik ke dashboard sesuai role-nya
        return redirect()->route('dashboard');
    }
}
