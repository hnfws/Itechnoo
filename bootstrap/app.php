<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Hapus type hint (Request $request) agar tidak error
        $middleware->redirectTo(
            guests: fn () => route('admin.login')
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
