<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        $user = auth()->user();

        // Super Admin has bypass access to everything
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // Check if user's role is in the allowed list of roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
