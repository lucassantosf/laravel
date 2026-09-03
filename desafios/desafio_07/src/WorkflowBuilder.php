<?php 

declare(strict_types=1);

namespace Desafio07;

use Desafio07\Interfaces\SagaStepInterface;
use Desafio07\SagaOrchestrator;

class WorkflowBuilder{

    private SagaOrchestrator $orchestrator;

    private function __construct(){
        $this->orchestrator = new SagaOrchestrator();
    }

    public static function criar(): self{
        return new WorkflowBuilder();
    }

    public function addStep(SagaStepInterface $step): self{
        $this->orchestrator->adicionarStep($step);
        return $this;
    }

    public function build(): SagaOrchestrator{
        return $this->orchestrator;
    }

}