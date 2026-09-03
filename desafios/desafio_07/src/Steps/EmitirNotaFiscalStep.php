<?php

declare(strict_types=1);

namespace Desafio07\Steps;

use Desafio07\Interfaces\SagaStepInterface;
use Desafio07\SagaContext;
use Desafio07\Exceptions\SagaExecucaoException;

class EmitirNotaFiscalStep implements SagaStepInterface{

    private float $valorMinimo;

    public function __construct($valorMinimo = 10.0){
        $this->valorMinimo = $valorMinimo;
    }

    public function getNome(): string{
        return "Emitir Nota Fiscal";
    }

    public function executar(SagaContext $context): bool{
        $simular = $context->get('simular_falha_fiscal');

        if(!empty($simular) && $simular){
            throw new SagaExecucaoException("SEFAZ indisponível. Falha na emissão da NF."); 
        }
        $context->set('nota_fiscal_id','NF-998877');

        echo PHP_EOL."[STEP OK] Nota Fiscal NF-998877 emitida.".PHP_EOL;
        return true;
    }

    public function compensar(SagaContext $context): void{
        $context->set('nota_fiscal_id',null);
        echo PHP_EOL."[COMPENSAÇÃO] Nota Fiscal cancelada junto à SEFAZ.".PHP_EOL;
    }
}
