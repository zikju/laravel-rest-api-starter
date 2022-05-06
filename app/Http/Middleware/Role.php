<?php

namespace App\Http\Middleware;

use App\Helpers\Respond;
use Auth;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Role
{
    /**
     * Check User role.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param mixed ...$roles
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next, ...$roles): JsonResponse
    {
        // Get current authorized user model
        $user = Auth::user();
        if (! $user) {
            return Respond::error('Authentication is required', 401);
        }

        // User with 'admin' role can skip check and access everything
        if($user->role === 'admin') {
            return $next($request);
        }

        // Check if current authorized user has any role from passed roles-list
        foreach($roles as $role) {
            if($user->role === $role) {
                return $next($request);
            }
        }

        return Respond::error("Access denied. You don't have permissions", 403);
    }
}
