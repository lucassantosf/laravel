<?php

declare(strict_types=1);

namespace Desafio07\Steps;

use Desafio07\Interfaces\SagaStepInterface;
use Desafio07\SagaContext;
use Desafio07\Exceptions\SagaExecucaoException;

class EnviarNotificacaoStep implements SagaStepInterface{

    public function getNome(): string{
        return "Enviar Notificação";
    }

    public function executar(SagaContext $context): bool{
        $context->set('notificacao_enviada',true);

        echo PHP_EOL."[STEP OK] Notificação de confirmação enviada ao cliente.".PHP_EOL;
        return true;
    }

    public function compensar(SagaContext $context): void{
        $context->set('notificacao_cancelamento_enviada',true);
        echo PHP_EOL."[COMPENSAÇÃO] E-mail de aviso de cancelamento enviado ao cliente.".PHP_EOL;
    }
}