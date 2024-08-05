<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\SendDataToQueue;
use App\Listeners\PedidoStatusChangedListener;
use App\Events\ExampleEvent;
use App\Events\PedidoStatusChangedEvent;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ExampleEvent::class => [
            SendDataToQueue::class,
        ],
        PedidoStatusChangedEvent::class => [
            PedidoStatusChangedListener::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}