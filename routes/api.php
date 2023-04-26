<?php

use App\Http\Controllers\{AuthController, ProductController, UserController};
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/register-admin', [AuthController::class, 'registerAdmin'])->name('auth.register-admin');
        Route::apiResource('users', UserController::class);
        Route::apiResource('products', ProductController::class);
    });
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
});
