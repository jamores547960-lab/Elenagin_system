<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckUserRole; // <-- NEW: Import your middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // ADD YOUR CUSTOM ROLE MIDDLEWARE HERE
        // This registers the alias 'role' which you use in routes.
        $middleware->alias([
            'role' => CheckUserRole::class, // <-- ADD THIS LINE
        ]);
        
        // You might want to add web or api middleware groups here if needed
        // $middleware->web(....);
        // $middleware->api(....);
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();