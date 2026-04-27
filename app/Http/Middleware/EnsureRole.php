<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user('sanctum');

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please log in first.',
            ], 401);
        }

        // super_admin bypass — can do everything
        if ($user->roles()->where('key', 'super_admin')->exists()) {
            return $next($request);
        }

        // Check if user has one of the required roles
        if (!$user->roles()->whereIn('key', $roles)->exists()) {
            $userRoles = $user->roles()->pluck('name')->join(', ') ?: 'none';

            return response()->json([
                'success' => false,
                'message' => 'Access denied. You do not have permission to perform this action.',
                'required_roles' => $roles,
                'your_role'      => $userRoles,
            ], 403);
        }

        return $next($request);
    }
}
