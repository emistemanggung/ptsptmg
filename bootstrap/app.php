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
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,

            // Middleware verifikasi webhook dari Layanan Madrasah.
            'verify.webhook' => \App\Http\Middleware\VerifyLayananMadrasahWebhookSecret::class,
        ]);

        // Route webhook (prefix /webhook) dikecualikan dari verifikasi CSRF
        // karena dipanggil server-to-server oleh aplikasi Layanan Madrasah
        // (tanpa cookie session / token CSRF browser). Diamankan sendiri
        // lewat header X-Webhook-Secret + middleware 'verify.webhook'.
        $middleware->validateCsrfTokens(except: [
            'webhook/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
