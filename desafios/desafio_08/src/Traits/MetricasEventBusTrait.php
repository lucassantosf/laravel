<?php 

declare(strict_types=1);

namespace Desafio08\Traits;

use Desafio08\Evento;

trait MetricasEventBusTrait{

    private int $eventosPublicados = 0;
    private int $handlersExecutados = 0;
    private int $handlersFalharam = 0;
    private int $eventosReenfileirados = 0;
    private int $eventosDeadLetter = 0;
    private array $deadLetterQueue = [];

    public function incrementarPublicado(): void{
        $this->eventosPublicados++;
    } 

    public function incrementarHandlerExecutado(): void{
        $this->handlersExecutados++;
    } 

    public function incrementarHandlerFalhou(): void{
        $this->handlersFalharam++;
    } 

    public function incrementarReenfileirado(): void{
        $this->eventosReenfileirados++;
    } 

    public function incrementarDeadLetter(): void{
        $this->eventosDeadLetter++;
    } 

    public function adicionarDeadLetter(Evento $evento): void{
        $this->deadLetterQueue[] = $evento;
    }

    public function getDeadLetterQueue(): array{
        return $this->deadLetterQueue;
    }

    public function getResumoMetricas(): array{
        return [
            'eventosPublicados'=>$this->eventosPublicados,
            'handlersExecutados'=>$this->handlersExecutados,
            'handlersFalharam'=>$this->handlersFalharam,
            'eventosReenfileirados'=>$this->eventosReenfileirados,
            'eventosDeadLetter'=>$this->eventosDeadLetter, 
        ];
    }

}