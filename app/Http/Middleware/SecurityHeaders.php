<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = config('app.env') === 'production' && ! $request->isSecure()
            ? redirect()->secure($request->getRequestUri(), 301)
            : $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'; ".
            "object-src 'none'; img-src 'self' data: blob: https:; font-src 'self' data:; ".
            "style-src 'self' 'unsafe-inline' http://localhost:5173 http://127.0.0.1:5173 https://localhost:5173; ".
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:5173 http://127.0.0.1:5173 https://localhost:5173; ".
            "connect-src 'self' ws://localhost:5173 ws://127.0.0.1:5173 wss://localhost:5173",
        );

        return $response;
    }
}
