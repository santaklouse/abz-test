<?php

use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api/v1.php'));
        }
    )
    ->withMiddleware(fn (Middleware $middleware) => $middleware->api(append: [ForceJsonResponse::class]))
    ->withExceptions(function (Exceptions $exceptions) {
        //
//        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
//            if ($request->is('api/*')) {
//                return response()->json([
//                    'success' => false,
//                    "message" => "Access token missing or invalid."
//                ], 403);
//            }
//        });
//        $exceptions->render(function (TokenMismatchException $e, Request $request) {
//            if ($request->is('api/*')) {
//                return response()->json([
//                    'success' => false,
//                    "message" => "The token expired."
//                ], 401);
//            }
//        });

        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }
            return $request->expectsJson();
        });
    })->create();
