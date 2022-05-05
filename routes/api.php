<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RefreshTokensController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\PasswordRecoveryController;
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
            ->name('registration');

        /* Confirm Email */
        Route::put('register/confirm', [RegistrationController::class, 'confirmEmail'])
            ->name('registration.confirm');

    });

    /* Password Recovery */
    Route::put('recovery/send-email', [PasswordRecoveryController::class, 'sendConfirmationEmail'])
        ->middleware(['middleware' => 'throttle:2,1'])
        ->name('recovery.send');

    Route::put('recovery/change-password', [PasswordRecoveryController::class, 'changePassword'])
        ->middleware(['middleware' => 'throttle:2,1'])
        ->name('recovery.change');


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
        ->middleware('role:manager')
        ->name('users.create');

    /* Delete User */
    Route::delete('users', [UserController::class, 'delete'])
        ->middleware('role:manager')
        ->name('users.delete');
});


/**
 * ---------------------------------------
 * FALLBACK
 * ---------------------------------------
 *
 */

Route::fallback(static function (){
    return \App\Helpers\Respond::error('API resource not found', 404);
});

/* Route for tests... */
Route::get('test', [\App\Http\Controllers\TestController::class, 'test'])
    ->middleware('role:user')
    ->name('test');
