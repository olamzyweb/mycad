<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
require_once __DIR__.'/../vendor/autoload.php';


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //        
    })
    // ->withExceptions(function (Exceptions $exceptions) {
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (AuthenticationException $exception, $request) {
        return response()->json([
            'message' => 'Unauthenticated.'
        ], 401);
    });

        // Handle validation errors for API
        $exceptions->render(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors' => $e->errors(),
                ], 422);
            }
        });
})->create();