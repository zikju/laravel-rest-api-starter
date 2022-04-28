<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
    Route::post('login', [AuthController::class, 'login'])->name('login');

    /*/ ONLY LOGGED IN */
    Route::middleware('auth')->group(function () {
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    });
    /* ONLY LOGGED IN /*/
});


/**
 * ---------------------------------------
 * [USERS] ENDPOINT
 * ---------------------------------------
 *
 * ENDPOINT PATH: api/users/*
 *
 */

/*/ ONLY LOGGED IN */
Route::middleware('auth')->group(function () {
    Route::post('users', [UserController::class, 'create'])->name('createUser');
    Route::delete('users', [UserController::class, 'delete'])->name('deleteUser');
});
/* ONLY LOGGED IN /*/
