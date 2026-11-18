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
    ->withMiddleware(function (Middleware $middleware): void {
        // Required on Laravel Cloud / HTTPS reverse proxies so sessions & CSRF work
        $middleware->trustProxies(at: '*');

        // Public registration form can 419 on Cloud when session cookies fail;
        // keep CSRF on admin routes, exempt only the public registration POST.
        $middleware->validateCsrfTokens(except: [
            'tanseeq-badminton',
            'tanseeq-run',
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
