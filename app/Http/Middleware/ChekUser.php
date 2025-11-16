<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ChekUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan sudah login
        if (!Auth::check()) {
            return redirect()->route('login.index')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek role user
        if (Auth::user()->role !== 'user') {
            return abort(403, 'Akses ditolak. Hanya untuk user.');
        }

        return $next($request);
    }
}
