<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Str;

class EnsureTokenHeadersAreExist
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Verify if 'Access Token' exist in request header
        $accessToken = $request->bearerToken();
        if (! $accessToken) {
            return response()->json([
                'status' => 'error',
                'message'=> 'INVALID_ACCESS_TOKEN'
            ], 400);
        }

        // Verify if 'Refresh Token' exist in request header
        $refreshToken = $request->header(env('JWT_REFRESH_TOKEN_HEADER_KEY'));
        if (! $refreshToken || ! Str::isUuid($refreshToken)) {
            return response()->json([
                'status' => 'error',
                'message'=> 'INVALID_REFRESH_TOKEN'
            ], 400);
        }

        return $next($request);
    }

}
