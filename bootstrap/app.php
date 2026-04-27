<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permission' => \App\Http\Middleware\RequirePermission::class,
            'active'     => \App\Http\Middleware\EnsureUserIsActive::class,
            'super_admin'=> \App\Http\Middleware\EnsureSuperAdmin::class,
            'admin'      => \App\Http\Middleware\EnsureAdmin::class,
            'teacher'    => \App\Http\Middleware\EnsureTeacher::class,
            'student'    => \App\Http\Middleware\EnsureStudent::class,
            'role'       => \App\Http\Middleware\EnsureRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Always return JSON for API routes — never raw HTML error pages
        $exceptions->render(function (
            \Symfony\Component\HttpKernel\Exception\HttpException $e,
            \Illuminate\Http\Request $request
        ) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $status = $e->getStatusCode();

                $messages = [
                    401 => 'Unauthenticated. Please log in first.',
                    403 => 'Access denied. You do not have permission to perform this action.',
                    404 => 'The requested resource was not found.',
                    405 => 'HTTP method not allowed.',
                    422 => 'Validation failed.',
                    500 => 'An internal server error occurred.',
                ];

                return response()->json([
                    'success' => false,
                    'message' => $messages[$status] ?? ($e->getMessage() ?: 'An error occurred.'),
                ], $status);
            }
        });

    })->create();
