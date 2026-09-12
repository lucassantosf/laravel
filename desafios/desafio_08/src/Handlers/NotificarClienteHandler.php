<?php 

declare(strict_types=1);

namespace Desafio08\Handlers;

use Desafio08\Evento;
use Desafio08\Interfaces\HandlerEventoInterface;
use Desafio08\Exceptions\EventoFalhouException;

class NotificarClienteHandler implements HandlerEventoInterface{

    public function getNome(): string{
        return "Notificar Cliente";
    }

    public function getTopicoDeInteresse(): string{
        return "order.created";
    }

    public function getPrioridade(): int{
        return 2;
    }

    public function processar(Evento $evento): bool{
        $payload = $evento->getPayload();

        if(empty($payload['email']))
            throw new EventoFalhouException("Cliente sem e-mail válido.");
            
        echo PHP_EOL."[HANDLER OK] E-mail de confirmação enviado para {$payload['email']}.".PHP_EOL;
        return true;
    }

}