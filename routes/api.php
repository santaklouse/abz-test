<?php

use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckAccessToken;
use App\Models\AccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/positions', [PositionController::class, 'index']);

Route::get('/token', function (Request $request) {
    return response()->json([
        'success' => true,
        'token' => AccessToken::newToken()
    ]);
});

Route::post('/token', function (Request $request) {
    $token = AccessToken::findToken($request->post('token'));
    if ($token === NULL) {
        return response()->json([
            'success' => false,
            'message' => 'Token not found'
        ]);
    }

    if ($token->isExpired()) {
        return response()->json([
            'success' => true,
            'message' => 'Token expired'
        ]);
    }

    if ($token->isUsed()) {
        return response()->json([
            'success' => true,
            'message' => 'Token already used.'
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Token is valid'
    ]);
});

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{userId}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store'])
    ->middleware(CheckAccessToken::class)
;

