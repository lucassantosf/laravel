<?php

namespace App\Listeners;

use App\Events\ExampleEvent;
use App\Jobs\ProcessEventJob;

class SQSListener
{
    public function handle(ExampleEvent $event)
    {
        // Disparar o job para processar a mensagem
        ProcessEventJob::dispatch($event->data);
    }
}
