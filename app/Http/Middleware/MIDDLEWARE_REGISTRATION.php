<?php

// ====================================================================
// MIDDLEWARE REGISTRATION for Laravel 11+
// ====================================================================
// Add these middleware aliases in your bootstrap/app.php file.
//
// In bootstrap/app.php, update the ->withMiddleware() section:
//
// ->withMiddleware(function (Middleware $middleware) {
//     $middleware->alias([
//         'role' => \App\Http\Middleware\CheckRole::class,
//         'business.access' => \App\Http\Middleware\CheckBusinessAccess::class,
//         'license.check' => \App\Http\Middleware\CheckLicenseStatus::class,
//     ]);
// })
//
// ====================================================================
// For older Laravel versions (Laravel 10 or below), add to app/Http/Kernel.php:
//
// protected $middlewareAliases = [
//     // ... existing middleware ...
//     'role' => \App\Http\Middleware\CheckRole::class,
//     'business.access' => \App\Http\Middleware\CheckBusinessAccess::class,
//     'license.check' => \App\Http\Middleware\CheckLicenseStatus::class,
// ];
// ====================================================================
