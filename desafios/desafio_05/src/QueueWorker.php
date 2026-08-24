<?php 

declare(strict_types=1);

namespace Desafio05;
use Desafio05\Traits\EstatisticasFilaTrait;
use Desafio05\JobAbstract;
use Desafio05\Interfaces\JobMiddlewareInterface;
use Desafio05\Exceptions\FilaVaziaException;
use Desafio05\Exceptions\JobFalhouException;
use Desafio05\Enums\StatusJob;
 
class QueueWorker{

    use EstatisticasFilaTrait;

    protected array $fila = [];
    protected array $deadLetterQueue = [];
    protected array $middlewares = [];

    public function push(JobAbstract $job): void{
        $this->fila[] = $job;
    }

    public function adicionarMiddleware(JobMiddlewareInterface $middleware): void{
        $this->middlewares[] = $middleware;
    }

    public function getDeadLetterQueue(): array{
        return $this->deadLetterQueue;
    }

    public function processarFila(): void{

        if(empty($this->fila))
            throw new FilaVaziaException("A fila está vazia.");

        usort($this->fila, function (JobAbstract $a, JobAbstract $b) {
            return $a->getPrioridade()->value <=> $b->getPrioridade()->value;
        });

        while(!empty($this->fila)){

            $job = array_shift($this->fila);

            try {

                $job->setStatus(StatusJob::PROCESSANDO);

                $job->executar();

                $job->setStatus(StatusJob::CONCLUIDO);

                $this->incrementarConcluido();

            } catch (JobFalhouException $e) {
                $job->incrementarTentativa();

                if($job->isMaxTentativasExcedidas()){
                    $job->setStatus(StatusJob::FALHOU);
                    $this->deadLetterQueue[] = $job;
                    echo PHP_EOL.PHP_EOL."[DLQ] Job ID: {$job->getId()} excedeu o limite de {$job->getMaxTentativas()} tentativas e foi enviado para a Dead Letter Queue. Erro: {$e->getMessage()}";
                    $this->incrementarDeadLetter();
                }else{
                    echo PHP_EOL.PHP_EOL."[RETRY] Tentativa {$job->getTentativaAtual()}/{$job->getMaxTentativas()} falhou para o Job ID: {$job->getId()}. Devolvendo para a fila...";
                    $this->fila[] = $job;
                    $this->incrementarRetry();
                }
            }
        }
            
    }
}  