<?php 

declare(strict_types=1);

namespace Desafio08\Handlers;

use Desafio08\Evento;
use Desafio08\Interfaces\HandlerEventoInterface;
use Desafio08\Exceptions\EventoFalhouException;

class AtualizarEstoqueHandler implements HandlerEventoInterface{

    public function getNome(): string{
        return "Atualizar Estoque";
    }

    public function getTopicoDeInteresse(): string{
        return "order.created";
    }

    public function getPrioridade(): int{
        return 1;
    }

    public function processar(Evento $evento): bool{
        $payload = $evento->getPayload();

        if(!empty($payload['simular_falha']))
            throw new EventoFalhouException("Estoque indisponível para o produto ");

        $produto = $payload['produto'];
            
        echo PHP_EOL."[HANDLER OK] Estoque reservado para o produto {$produto}.".PHP_EOL;
        return true;
    }

}