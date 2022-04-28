<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\UserSession;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Retrieve the validated input data...
        $validatedRequestData = $request->validated();

        // Validate credentials
        if (!auth()->validate($validatedRequestData)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $accessToken = auth()->attempt($validatedRequestData);
        $refreshToken = (new UserSession())->createSession($request->getClientIp());

        return $this->respondWithTokens($accessToken, $refreshToken);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json([
            'status' => 'ok',
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param string $accessToken
     * @param string $refreshToken
     * @return JsonResponse
     */
    protected function respondWithTokens(string $accessToken, string $refreshToken): JsonResponse
    {
        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
