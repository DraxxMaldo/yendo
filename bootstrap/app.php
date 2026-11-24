<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CuentaActivaMiddleware;
use App\Http\Middleware\RolMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Verificamos las cuentas en todas las solicitudes del grupo web.
        $middleware->web(append: [
            CuentaActivaMiddleware::class,
        ]);

        // Registramos el alias del middleware de roles para usarlo en web.php
        $middleware->alias([
            'rol' => RolMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
