<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::get('/get-user', [AuthController::class, 'getUser']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/login-admin', [AuthController::class, 'login_admin']);
    // for logout admin is the same as user
});

Route::group(['prefix' => 'auth', 'middleware' => ['auth:sanctum']], function () {
    Route::post('/set-notification-token', [AuthController::class, 'setNotificationToken']);
});
