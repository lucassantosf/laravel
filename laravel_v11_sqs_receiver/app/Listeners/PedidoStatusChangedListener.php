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
        /** pode jogar ou para a job se quiser */
        // ProcessEventJob::dispatch($event->data);
        /** ou pode tratar os dados aqui mesmo no listener */

        DB::table('failed_jobs')->insert([
            'uuid' => Str::uuid(),
            'connection' => 'test event receiver listener PedidoStatusChanged',
            'queue' => '',
            'payload' => json_encode($event->data),
            'exception' => '',
            'failed_at' => date("Y-m-d H:i:s"),
        ]);
    }
}