<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\ChatUserController;
use App\Http\Controllers\Api\V1\MessageController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return UserResource::make($request->user());
})->middleware('auth:sanctum');

Route::prefix('/v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/messages/{chat}', [MessageController::class, 'messages'])->name('messages');
        Route::post('/message/{chat}', [MessageController::class, 'message'])->name('message');

        Route::prefix('/chats')->group(function () {
            Route::get('/', [ChatUserController::class, 'index']);
            Route::post('/', [ChatUserController::class, 'store']);
        });
    });
});
