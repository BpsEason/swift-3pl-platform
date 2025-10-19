<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\ShipmentCreated;
use App\Listeners\SendWebhookNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * 應用程式的事件與監聽器對應。
     * @var array
     */
    protected $listen = [
        ShipmentCreated::class => [
            SendWebhookNotification::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
