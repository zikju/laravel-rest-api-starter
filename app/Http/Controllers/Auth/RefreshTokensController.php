<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RefreshTokensController extends Controller
{
    /**
     * Refresh Tokens.
     * - Generate a new pair of 'Access Token' and 'Refresh Token'
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshTokens(Request $request): JsonResponse
    {
        // Get 'Refresh Token' from Request header
        $refreshToken = $request->header(env('JWT_REFRESH_TOKEN_HEADER_KEY'));

        // Get current User session from database and backup it to variable
        $userSession = UserSession::query()
            ->where('refresh_token', '=', $refreshToken)
            ->where('expires_at', '>=', now())
            ->first();

        // Delete User session from database
        UserSession::where('refresh_token', $refreshToken)->delete();

        // Handle invalid User session
        if (empty($userSession)) {
            // Return response with error
            return response()->json([
                'status' => 'error',
                'message'=> 'INVALID_REFRESH_TOKEN'
            ], 400);
        }

        // Create new 'Refresh Token' and User session in database
        $refreshToken = (new UserSession())->createSession($userSession['user_id'], $request->getClientIp());

        // Create new 'Access Token'
        $accessToken = auth()->refresh();

        return response()->json([
            'status' => 'ok',
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => env('JWT_TTL') * 60 // in minutes
        ]);
    }
}
