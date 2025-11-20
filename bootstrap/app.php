<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

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
        
        // Prevent search engine indexing for all web routes
        $middleware->web(append: [
            \App\Http\Middleware\PreventIndexing::class,
        ]);
        
        // Configure rate limiters
        $middleware->throttleApi();
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

        // Handle all other exceptions for API routes
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                
                // Don't override validation exceptions
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return null;
                }

                // Get error message
                $message = $e->getMessage();
                
                // For production, hide sensitive error details
                if (app()->environment('production') && $statusCode === 500) {
                    $message = 'An internal server error occurred. Please try again later.';
                }

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], $statusCode);
            }
        });
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Run scheduled account deletion daily at midnight
        $schedule->command('accounts:delete-scheduled')
            ->daily()
            ->at('00:00')
            ->timezone('UTC');
    })->create();
