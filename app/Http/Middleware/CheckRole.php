<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            // Jika pengguna tidak terautentikasi atau tidak memiliki peran yang sesuai
            return redirect('/'); // Ganti dengan halaman yang sesuai
        }

        return $next($request);
    }
}
