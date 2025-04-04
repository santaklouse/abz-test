<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AccessToken;
use Illuminate\Http\Request;

class TokenController extends Controller
{

    public function create()
    {
        return response()->json([
            'success' => true,
            'token' => AccessToken::newToken()
        ]);
    }

    public function check(Request $request)
    {
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
    }

}
