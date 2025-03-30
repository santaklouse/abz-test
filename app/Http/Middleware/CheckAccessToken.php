<?php

namespace App\Http\Middleware;

use App\Models\AccessToken;
use Closure;
use Illuminate\Support\Facades\Log;


class CheckAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        Log::info('Processing token.', [
            'token' => $token,
        ]);

        if ($token === NULL) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 403);
        }

        $token = AccessToken::findToken($token);
        if ($token->isExpired() || $token->isUsed()) {
            Log::info('Token was expired.', [
                'token' => $token,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Token expired.'
            ], 401);
        }

        Log::info('Token valid.', [
            'token' => $token,
        ]);

        $token->makeUsed();
        return $next($request);
    }


}
