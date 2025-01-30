<?php

use App\Http\Controllers\API\NoteController;
use App\Http\Controllers\API\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('user')->group(function () {
    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('login', [UserAuthController::class, 'login']);

    Route::middleware('auth:user')->group(function () {
        Route::get('profile', [UserAuthController::class, 'user']);

        Route::middleware('tenant:auth')->group(function () {
            Route::get('all-notes', [NoteController::class, 'index']);
            Route::post('create-note', [NoteController::class, 'store']);
        });
    });
});
