<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan masuk menggunakan akun Admin atau Super Admin.');
        }

        if (!auth()->user()->isAdmin()) {
            return redirect()->route('admin.login')->with('error', 'Akses ditolak. Halaman tersebut khusus untuk Petugas Perpustakaan (Admin & Super Admin). Akun yang sedang aktif (' . auth()->user()->email . ') adalah akun Pelanggan. Silakan masuk menggunakan akun resmi pegawai.');
        }

        return $next($request);
    }
}
