<?php

namespace App\Listeners;

use App\Events\ExampleEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Queue;

class SendDataToQueue implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ExampleEvent $event)
    {
        /**
         * precisa ter esta classe como representação ao listener do receiver
         */
    }
}