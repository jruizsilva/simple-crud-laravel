<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::apiResource('posts', PostController::class);

Route::middleware('auth')->group(function () {
    Route::get('hello-world', function () {
        return response()->json(['message' => 'Hello World']);
    });
});