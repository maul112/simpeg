<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class EnsureGuestDataExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $name = Cookie::get('guest_name');
        $address = Cookie::get('guest_address');

        if (!$name || !$address) {
            return redirect()->route('tamu.masukForm')
            ->with('error', 'Silakan isi data diri Anda terlebih dahulu.');
        }
        return $next($request);
    }
}
