<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Respond;
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

        // Create 'Access Token'
        if (! $accessToken = auth()->attempt($validatedRequestData)) {
            return Respond::error('INVALID_CREDENTIALS', 401);
        }

        // Check account status
        if (auth()->user()->status !== 'active') {
            return Respond::error('ACCOUNT_NOT_ACTIVE', 403);
        }

        // Create 'Refresh Token'
        $refreshToken = (new UserSession())->createSession(auth()->id(), $request->getClientIp());

        $responseData = [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];

        return Respond::ok('Successfully logged in!', $responseData);
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
        // Delete current User session from database
        $refreshToken = $request->header(env('JWT_REFRESH_TOKEN_HEADER_KEY'));
        UserSession::where('refresh_token', $refreshToken)->delete();

        return Respond::ok('Successfully logged out!');
    }
}
