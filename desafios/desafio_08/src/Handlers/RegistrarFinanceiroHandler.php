<?php 

declare(strict_types=1);

namespace Desafio08\Handlers;

use Desafio08\Evento;
use Desafio08\Interfaces\HandlerEventoInterface;
use Desafio08\Exceptions\EventoFalhouException;

class RegistrarFinanceiroHandler implements HandlerEventoInterface{

    public function getNome(): string{
        return "Registrar Financeiro";
    }

    public function getTopicoDeInteresse(): string{
        return "order.paid";
    }

    public function getPrioridade(): int{
        return 1;
    }

    public function processar(Evento $evento): bool{
        $payload = $evento->getPayload();
        $valor = $payload['valor'];

        if($valor < 1)
            throw new EventoFalhouException("Pagamento sem valor mínimo para conciliação.");
            
        echo PHP_EOL."[HANDLER OK] Pagamento de R$ {$valor} conciliado no financeiro.".PHP_EOL;
        return true;
    }

} 