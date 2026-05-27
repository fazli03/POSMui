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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Periksa apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Periksa apakah user memiliki role yang sesuai
        if (!$this->hasRole($user, $role)) {
            // Redirect ke dashboard sesuazi role user
            return $this->redirectToUserDashboard($user);
        }

        return $next($request);
    }

    /**
     * Periksa apakah user memiliki role tertentu
     */
    private function hasRole($user, string $requiredRole): bool
    {
        // Asumsikan user memiliki kolom 'role' atau relasi roles
        // Sesuaikan dengan struktur database Anda

        if (method_exists($user, 'hasRole')) {
            // Jika menggunakan package seperti Spatie Permission
            return $user->hasRole($requiredRole);
        }

        // Jika menggunakan kolom role sederhana
        if (isset($user->role)) {
            return $user->role === $requiredRole;
        }

        // Jika menggunakan relasi roles
        if ($user->relationLoaded('roles') || method_exists($user, 'roles')) {
            return $user->roles()->where('name', $requiredRole)->exists();
        }

        return false;
    }

    /**
     * Redirect user ke dashboard sesuai role mereka
     */
    private function redirectToUserDashboard($user)
    {
        $userRole = $this->getUserRole($user);

        switch ($userRole) {
            case 'owner':
                return redirect('/owner');
            case 'kasir':
                return redirect('/kasir');
            case 'dapur':
                return redirect('/dapur');
            default:
                // Jika role tidak dikenali, redirect ke halaman utama
                return redirect('/');
        }
    }

    /**
     * Dapatkan role user
     */
    private function getUserRole($user): string
    {
        if (method_exists($user, 'getRoleNames')) {
            // Untuk Spatie Permission
            $roles = $user->getRoleNames();
            return $roles->first() ?? 'guest';
        }

        if (isset($user->role)) {
            return $user->role;
        }

        if ($user->relationLoaded('roles') || method_exists($user, 'roles')) {
            $role = $user->roles()->first();
            return $role ? $role->name : 'guest';
        }

        return 'guest';
    }
}
