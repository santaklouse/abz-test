<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function jsonResponse(string $message, int $code, array $additionalData = []): JsonResponse
    {
        $success = $code >= 200 && $code < 300;

        return response()->json([
            'success' => $success,
            'message' => $message,
            ...$additionalData
        ], $code);
    }

}
