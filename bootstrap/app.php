<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\CheckPaymentCleared;
use App\Http\Middleware\RestrictBlockedStudent;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'check.payment' => CheckPaymentCleared::class,
        'restrict.student' => RestrictBlockedStudent::class,

    ]);
})
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
