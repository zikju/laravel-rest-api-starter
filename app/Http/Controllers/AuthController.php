<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\UserSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
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

        return $this->respondWithTokens($accessToken, $refreshToken);
    }

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

        return $this->respondWithTokens($accessToken, $refreshToken);
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

    /**
     * Respond with Tokens.
     * - Return response with a pair of 'Access Token' and 'Refresh Token'
     *
     * @param string $accessToken
     * @param string $refreshToken
     * @return JsonResponse
     */
    protected function respondWithTokens(string $accessToken, string $refreshToken): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => env('JWT_TTL') * 60 // in minutes
        ]);
    }
}
