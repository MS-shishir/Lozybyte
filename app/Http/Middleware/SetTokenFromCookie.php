<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetTokenFromCookie
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
        if ($request->hasCookie('lozybyte_admin_token')) {
            $token = $request->cookie('lozybyte_admin_token');
            if ($token) {
                $request->headers->set('Authorization', 'Bearer ' . $token);
            }
        }

        return $next($request);
    }
}
