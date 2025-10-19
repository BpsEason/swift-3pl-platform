<?php

return [
    'tenant_model' => \App\Models\Tenant::class,
    'id_generator' => \Stancl\Tenancy\UidGenerator\UniqueIdGenerator::class,
    'database' => [
        'central_connection' => env('TENANCY_DB_CONNECTION', 'mysql'),
        'tenant_connection' => env('TENANCY_DB_CONNECTION', 'mysql'),
        'suffix' => env('TENANCY_FILESYSTEM_SUFFIX', 'tenant_'),
    ],
    'bootstrappers' => [
        \Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        \Stancl\Tenancy\Bootstrappers\RedisTenancyBootstrapper::class,
    ],
    'routes' => [
        'home_url' => '/',
        'tenant_web' => base_path('routes/tenant/web.php'),
        'tenant_api' => null, 
    ],
    'features' => [
        \Stancl\Tenancy\Features\UniversalRoutes::class,
    ],
];
