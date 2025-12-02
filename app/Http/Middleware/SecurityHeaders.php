<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // XSS Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Content Security Policy - disesuaikan untuk Vite + React + Inertia
        // Lebih permisif untuk development, bisa diperketat di production
        $csp = "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* ws://localhost:*; " .
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net; " .
            "font-src 'self' data: https://fonts.bunny.net; " .
            "img-src 'self' data: https: blob:; " .
            "connect-src 'self' http://localhost:* ws://localhost:* wss://localhost:*;";

        // Hanya set CSP di production untuk keamanan maksimal
        // Di development, CSP bisa mengganggu hot reload Vite
        if (app()->environment('production')) {
            $response->headers->set('Content-Security-Policy', $csp);
        }

        // Referrer Policy - jangan expose URL ke external sites
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy - disable unnecessary features
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), interest-cohort=()'
        );

        return $response;
    }
}
