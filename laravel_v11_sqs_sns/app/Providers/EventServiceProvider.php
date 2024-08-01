<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\SQSListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'ExampleEvent' => [
            SQSListener::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}