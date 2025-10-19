<?php
// 核心隊列配置，定義 picking_speed 和 webhook_high_priority 隊列
return [
    'default' => env('QUEUE_CONNECTION', 'redis'),
    'connections' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => 90,
            'block_for' => 5,
        ],
    ],
    'queues' => [
        'default' => 'default',
        'picking_speed' => 'picking_speed', // 專門用於揀貨優化的快速隊列
        'webhook_high_priority' => 'webhook_high_priority', // 專門用於 Webhook 的高優先級隊列
    ],
    'failed' => [
        'database' => 'mysql',
        'table' => 'failed_jobs',
    ],
];
