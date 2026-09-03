<?php

declare(strict_types=1);

namespace Desafio07\Steps;

use Desafio07\Interfaces\SagaStepInterface;
use Desafio07\SagaContext;

class ReservarEstoqueStep implements SagaStepInterface{

    public function getNome(): string{
        return "Reservar Estoque";
    }

    public function executar(SagaContext $context): bool{
        $context->set('estoque_reservado',true);
        echo PHP_EOL."[STEP OK] Estoque reservado para o pedido.".PHP_EOL;
        return true;
    }

    public function compensar(SagaContext $context): void{
        $context->set('estoque_reservado',false);
        echo PHP_EOL."[COMPENSAÇÃO] Reserva de estoque cancelada/devolvida.".PHP_EOL;
    }
}