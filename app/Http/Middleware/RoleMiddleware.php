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

        if (!$user) {
            abort(403, 'No authenticated user');
        }

        if (!$user->role) {
            abort(403, 'User has no role relation');
        }

        // 🔴 TEMP DEBUG — THIS IS THE KEY LINE
        if (!in_array($user->role->name, $roles)) {
            abort(
                403,
                'User role = '.$user->role->name.
                ' | Allowed roles = '.implode(',', $roles)
            );
        }

        return $next($request);
    }
}
