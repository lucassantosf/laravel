<?php 

declare(strict_types=1);

namespace Desafio08\Outbox;

use Desafio08\Evento;

class OutboxMessage{
    private Evento $evento;
    private string $estado = 'PENDENTE';

    public function __construct(Evento $evento, string $estado = 'PENDENTE')
    {
        $this->evento = $evento;
        $this->estado = $estado;
    }
    
    public function getEvento(): Evento{
        return $this->evento;
    }

    public function getEstado(): string{
        return $this->estado;
    }

    public function setEstado(string $estado): void{
        $this->estado = $estado;
    }
} 