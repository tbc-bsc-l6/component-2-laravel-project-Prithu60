<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();

        // Not logged in
        if (!$user) {
            abort(403, 'No authenticated user');
        }

        // Role relation missing
        if (!$user->role) {
            abort(403, 'User has no role relation');
        }

        // ✅ THIS IS THE FIX
        // Your column is `role`, NOT `name`
        if (!in_array($user->role->role, $roles)) {
            abort(
                403,
                'User role = '.$user->role->role.
                ' | Allowed roles = '.implode(',', $roles)
            );
        }

        return $next($request);
    }
}
