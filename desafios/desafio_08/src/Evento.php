<?php 

declare(strict_types=1);

namespace Desafio08;

use Desafio08\Enums\PrioridadeEvento;
use Desafio08\Enums\StatusEvento;

class Evento{
    
    private string $id;
    private string $topico;
    private array $payload;
    private PrioridadeEvento $prioridade;
    private float $criadoEm;
    private int $tentativas = 0;
    private StatusEvento $status = StatusEvento::PUBLICADO;

    public function __construct(string $id, string $topico, array $payload, PrioridadeEvento $prioridade){
        $this->id = $id;
        $this->topico = $topico;
        $this->payload = $payload;
        $this->prioridade = $prioridade;
        $this->criadoEm = microtime(true);
    }

    public function getId(): string{
        return $this->id;
    }

    public function getTopico(): string{
        return $this->topico;
    }

    public function getPayload(): array{
        return $this->payload;
    }

    public function setPayload(array $payload): void{
        $this->payload = $payload;
    }

    public function getPrioridade(): PrioridadeEvento{
        return $this->prioridade;
    }

    public function getCriadoEm(): float{
        return $this->criadoEm;
    }

    public function getTentativas(): int{
        return $this->tentativas;
    }

    public function getStatus(): StatusEvento{
        return $this->status;
    }

    public function setStatus(StatusEvento $status): void{
        $this->status = $status;
    }

    public function incrementarTentativa(): void{
        $this->tentativas++;
    }

    public function isMaxTentativasExcedidas(int $max): bool{
        if($this->tentativas >= $max)
            return true;

        return false;
    }

}