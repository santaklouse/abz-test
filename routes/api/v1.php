<?php

use App\Http\Controllers\Api\V1\PositionsController;
use App\Http\Controllers\Api\V1\TokenController;
use App\Http\Controllers\Api\V1\UsersController;
use App\Http\Middleware\CheckAccessToken;
use Illuminate\Support\Facades\Route;

Route::get('/token', [TokenController::class, 'create']);
Route::post('/token', [TokenController::class, 'check']);

Route::apiResource('positions', PositionsController::class, ['only' => ['index']]);
Route::apiResource('users', UsersController::class, ['except' => ['update', 'destroy', 'edit', 'store']]);

Route::post('/users', [UsersController::class, 'store'])
    ->middleware(CheckAccessToken::class)
;




