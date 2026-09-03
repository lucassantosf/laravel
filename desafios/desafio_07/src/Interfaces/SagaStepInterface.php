<?php 

declare(strict_types=1);

namespace Desafio07\Interfaces;

use Desafio07\SagaContext;

interface SagaStepInterface{

    public function getNome(): string;
    public function executar(SagaContext $context): bool;
    public function compensar(SagaContext $context): void;

} 