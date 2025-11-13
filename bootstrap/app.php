<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);
        
        // Configure rate limiters
        $middleware->throttleApi();
    })
    ->withRateLimiting(function () {
        // Custom rate limiter for email verification check endpoint
        // Allows 60 requests per minute (12 requests per 5 seconds)
        \Illuminate\Support\Facades\RateLimiter::for('email-verification-check', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Customize validation error response for API routes
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                $errors = $e->errors();
                
                // Get the first error message from the first field
                $firstError = null;
                foreach ($errors as $fieldErrors) {
                    if (is_array($fieldErrors) && count($fieldErrors) > 0) {
                        $firstError = $fieldErrors[0];
                        break;
                    }
                }
                
                return response()->json([
                    'success' => false,
                    'message' => $firstError ?? 'The given data was invalid.',
                ], 422);
            }
        });
    })->create();
