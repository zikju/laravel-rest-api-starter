<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RefreshTokensController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\UserController;

use App\Http\Middleware\EnsureTokenHeadersAreExist;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * ---------------------------------------
 * [AUTH] ENDPOINTS
 * ---------------------------------------
 *
 * ENDPOINT PATH: api/auth/*
 *
 */
Route::prefix('auth')->group(function () {

    Route::middleware(['middleware' => 'throttle:5,1'])->group(function () {
        /* Login */
        Route::post('login', [LoginController::class, 'login'])
            ->name('auth.login');

        /* Refresh Tokens */
        Route::get('refresh-tokens', [RefreshTokensController::class, 'refreshTokens'])
            ->middleware(EnsureTokenHeadersAreExist::class)
            ->name('auth.refresh');

        /* Registration */
        Route::post('register', [RegistrationController::class, 'register'])
            ->name('auth.registration');

        /* Confirm Email */
        Route::put('confirm/{token}', [RegistrationController::class, 'confirmEmail'])
            ->name('auth.email.confirm');

    });


    Route::middleware('auth')->group(function () {
        /* Logout */
        Route::get('logout', [LoginController::class, 'logout'])
            ->middleware(EnsureTokenHeadersAreExist::class)
            ->name('auth.logout');
    });

});


/**
 * ---------------------------------------
 * [USERS] ENDPOINT
 * ---------------------------------------
 *
 * ENDPOINT PATH: api/users/*
 *
 */

Route::middleware('auth')->group(function () {
    /* Create User */
    Route::post('users', [UserController::class, 'create'])
        ->name('users.create');

    /* Delete User */
    Route::delete('users', [UserController::class, 'delete'])
        ->name('users.delete');
});


/**
 * ---------------------------------------
 * FALLBACK
 * ---------------------------------------
 *
 */

Route::fallback(function (){
    abort(404, 'API resource not found');
});

/* Route for tests... */
Route::get('test', [\App\Http\Controllers\TestController::class, 'test'])
    ->name('test');
