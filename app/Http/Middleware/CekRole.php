<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {
        // Cek apakah pengguna sudah login
        if (!session()->has('user_id')) {
            return redirect()->route('login');
        }

        // Cek apakah role pengguna diizinkan
        if (!in_array(session('role'), $roles, true)) {
            abort(403, 'Kamu tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
