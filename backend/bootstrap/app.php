<?php

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
    ->withMiddleware(function (Middleware $middleware) {
        // 1. API 中間件組 (確保 Sanctum 狀態型請求優先)
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // 2. 核心路由別名
        $middleware->alias([
            'auth.sanctum' => \Laravel\Sanctum\Http\Middleware\AuthenticateWithSanctum::class,
            'tenant' => \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
            'tenant.api' => \Stancl\Tenancy\Middleware\InitializeTenancyByRequestData::class,
        ]);
        
        // 3. 排除 Tenancy 中間件的路由
        $middleware->preventTenancy([
            '/', 
            '/api/orders/import', 
            '/api/inventory/*', 
            '/api/shipping/*',
        ]);
    })
    ->withExceptions(fn (Exceptions $exceptions) => [/* ... */])
    ->withEvents(fn ($events) => [
        // 註冊 Event Service Provider
        $events->register(\App\Providers\EventServiceProvider::class)
    ])
    ->create();
