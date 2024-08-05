<?php

namespace App\Listeners;

use App\Events\PedidoStatusChangedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessEventJob;

class PedidoStatusChangedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(PedidoStatusChangedEvent $event)
    {
        /**
         * precisa ter esta classe como representação ao listener do receiver
         */
    }
}