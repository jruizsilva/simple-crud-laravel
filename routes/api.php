<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\GuestMiddleware;
use App\Http\Middleware\AuthMiddleware;

Route::middleware(GuestMiddleware::class)->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
});
Route::middleware(AuthMiddleware::class)->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('me', [AuthController::class, 'me'])->name('me');
});

Route::apiResource('users', UserController::class);
Route::apiResource('posts', PostController::class);