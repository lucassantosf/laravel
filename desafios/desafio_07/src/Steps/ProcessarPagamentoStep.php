<?php

declare(strict_types=1);

namespace Desafio07\Steps;

use Desafio07\Interfaces\SagaStepInterface;
use Desafio07\SagaContext;
use Desafio07\Exceptions\SagaExecucaoException;

class ProcessarPagamentoStep implements SagaStepInterface{

    private float $valorMinimo;

    public function __construct($valorMinimo = 10.0){
        $this->valorMinimo = $valorMinimo;
    }

    public function getNome(): string{
        return "Processar Pagamento";
    }

    public function executar(SagaContext $context): bool{
        $valor_pedido = $context->get('valor_pedido');

        if(empty($valor_pedido) || $valor_pedido < $this->valorMinimo){
            throw new SagaExecucaoException("Pagamento recusado: valor abaixo do mínimo permitido de R$ {$this->valorMinimo}"); 
        }
        $context->set('pagamento_aprovado',true);

        echo PHP_EOL."[STEP OK] Pagamento processado com sucesso.".PHP_EOL;
        return true;
    }

    public function compensar(SagaContext $context): void{
        $context->set('pagamento_aprovado',false);
        echo PHP_EOL."[COMPENSAÇÃO] Pagamento estornado ao cliente.".PHP_EOL;
    }
}
