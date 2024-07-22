<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'isAdmin' => \App\Http\Middleware\IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontReportDuplicates();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Google\Service\Exception $e, Request $request) {
            if ($request->is('events/*')) {
                report($e->getMessage());
                return response()->json([
                    'message' => 'Something went wrong with Google services.'
                ], 500);
            }
        });
    })->create();
