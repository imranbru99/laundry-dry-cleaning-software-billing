<?php

use App\Http\Middleware\EnsureModuleEnabled;
use App\Http\Middleware\SetTenantFromSubdomain;
use App\Http\Middleware\SetTenantFromUser;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'tenant.user' => SetTenantFromUser::class,
            'tenant.subdomain' => SetTenantFromSubdomain::class,
            'module' => EnsureModuleEnabled::class,
        ]);

        $middleware->appendToGroup('web', [
            SetTenantFromSubdomain::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
