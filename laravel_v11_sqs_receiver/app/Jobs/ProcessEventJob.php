<?php 

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProcessEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        // Processar os dados do evento
        // Por exemplo, salvar dados no banco de dados ou chamar outro serviço
        DB::table('failed_jobs')->insert([
            'uuid' => Str::uuid(),
            'connection' => 'test event',
            'queue' => '',
            'payload' => json_encode($this->data),
            'exception' => '',
            'failed_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
