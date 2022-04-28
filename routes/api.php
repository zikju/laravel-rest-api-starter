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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

// Route::post('users', [UserController::class, 'create']);
// Route::delete('users', [UserController::class, 'delete']);

/**
 * ---------------------------------------
 * [AUTH] ENDPOINTS
 * ---------------------------------------
 */
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth')->group(function () {
        Route::get('logout', [AuthController::class, 'logout']);
    });

    // TODO: Register endpoint
});
