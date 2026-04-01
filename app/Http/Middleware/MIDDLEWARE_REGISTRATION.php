<?php

// Add these middleware aliases to your app/Http/Kernel.php
// In the $middlewareAliases (or $routeMiddleware for older Laravel) array:

/*
protected $middlewareAliases = [
    // ... existing middleware ...
    'role' => \App\Http\Middleware\CheckRole::class,
    'business.access' => \App\Http\Middleware\CheckBusinessAccess::class,
];
*/

// For Laravel 11+ with bootstrap/app.php, add this to your middleware configuration:

/*
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
        'business.access' => \App\Http\Middleware\CheckBusinessAccess::class,
    ]);
})
*/
