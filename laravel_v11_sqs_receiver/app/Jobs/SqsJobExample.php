<? 

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SqsJobExample extends Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload = null)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Decodifica o payload da mensagem SQS
            $payloadData = json_decode($this->payload, true);

            // Extrai a mensagem
            $messageData = $payloadData['data']['payload'] ?? [];

            // Adiciona o processamento da mensagem aqui, se necessário

            // Salva a mensagem processada na tabela failed_jobs para referência
            DB::table('failed_jobs')->insert([
                'uuid' => Str::uuid(),
                'connection' => 'sqs',
                'queue' => '',
                'payload' => json_encode([
                    'job' => get_class($this),
                    'data' => $messageData,
                ]),
                'exception' => 'sucesso', // Marca como sucesso
                'failed_at' => now(),
            ]);
        } catch (\Throwable $th) {
            // Se ocorrer uma exceção, insere os detalhes na tabela failed_jobs
            DB::table('failed_jobs')->insert([
                'uuid' => Str::uuid(),
                'connection' => 'sqs',
                'queue' => '',
                'payload' => json_encode([
                    'job' => get_class($this),
                    'data' => $this->payload,
                ]),
                'exception' => $th->getMessage() . ' on line ' . $th->getLine(),
                'failed_at' => now(),
            ]);
        }
    }
}
