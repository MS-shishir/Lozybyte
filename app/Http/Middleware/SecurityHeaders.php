<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (isset($response->headers)) {
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

            // Secure Content Security Policy (CSP)
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdn.jsdelivr.net https://www.googletagmanager.com https://connect.facebook.net; " .
                   "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; " .
                   "img-src 'self' data: https: http://localhost:8000 http://127.0.0.1:8000 https://www.lozybyte.com https://lozybyte.com https://connect.facebook.net; " .
                   "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; " .
                   "connect-src 'self' http://localhost:8000 http://127.0.0.1:8000 https://lozybyte.com https://www.lozybyte.com https://www.google-analytics.com https://connect.facebook.net; " .
                   "frame-src 'self' https://www.youtube.com https://player.vimeo.com; media-src 'self' https: http://localhost:8000 http://127.0.0.1:8000; object-src 'none'; base-uri 'self'; form-action 'self';";
            $response->headers->set('Content-Security-Policy', $csp);
        }

        return $response;
    }
}
