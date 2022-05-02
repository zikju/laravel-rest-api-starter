<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\UserSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Login user.
     * - Generate a pair of 'Access Token' and 'Refresh Token'
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Retrieve the validated input data...
        $validatedRequestData = $request->validated();

        // Validate credentials and create 'Access Token'
        if (! $accessToken = auth()->attempt($validatedRequestData)) {
            return response()->json([
                'status' => 'error',
                'message' => 'INVALID_CREDENTIALS'
            ], 401);
        }

        // Create 'Refresh Token'
        $refreshToken = (new UserSession())->createSession(auth()->id(), $request->getClientIp());

        return response()->json([
            'status' => 'ok',
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => env('JWT_TTL') * 60 // in minutes
        ]);
    }

    /**
     * Logout user.
     * - Invalidate 'Access Token' and delete User session from database.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        // Invalidate 'Access Token'
        auth()->logout();

        // Delete current User session from database
        $refreshToken = $request->header(env('JWT_REFRESH_TOKEN_HEADER_KEY'));
        UserSession::where('refresh_token', $refreshToken)->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Successfully logged out'
        ]);
    }
}
