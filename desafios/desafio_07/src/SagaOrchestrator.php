<?php 

declare(strict_types=1);

namespace Desafio07;

use Desafio07\Traits\AuditoriaSagaTrait;
use Desafio07\Enums\EstadoSaga;
use Desafio07\Interfaces\SagaStepInterface;
use Desafio07\Exceptions\WorkflowInvalidoException;
use Desafio07\Exceptions\SagaCompensacaoException;
use Desafio07\SagaContext;

class SagaOrchestrator{

    use AuditoriaSagaTrait;

    private array $steps = [];
    private EstadoSaga $estado = EstadoSaga::NAO_INICIADO;
    private array $stepsConcluidos = [];

    public function adicionarStep(SagaStepInterface $step): self{
        $this->steps[] = $step;
        return $this;
    }

    public function getEstado(): EstadoSaga{
        return $this->estado;
    }

    public function run(SagaContext $context): bool{
        
        if(empty($this->steps)){
            throw new WorkflowInvalidoException("Nenhum passo foi registrado no workflow.");            
        }

        $this->estado = EstadoSaga::EM_EXECUCAO;

        foreach($this->steps as $step){
            try {
                
                $step->executar($context);
                array_unshift($this->stepsConcluidos, $step);
                $this->registrarEvento($step->getNome(), 'EXECUCAO', 'Sucesso');

            } catch (\Exception $e) {

                $this->estado = EstadoSaga::FALHOU;
                $this->registrarEvento($step->getNome(), 'EXECUCAO', 'FALHA: ' . $e->getMessage());
                echo PHP_EOL."[ERRO SAGA] Falha no passo '{$step->getNome()}': {$e->getMessage()}".PHP_EOL;
                $this->executarCompensacao($context);
                return false;    

            }
        }


        $this->estado = EstadoSaga::SUCESSO;
        return true;
    }

    public function executarCompensacao(SagaContext $context): void{
        $this->estado = EstadoSaga::EM_COMPENSACAO;
        echo PHP_EOL."\n--- INICIANDO COMPENSAÇÃO DE SAGA (ROLLBACK EM ORDEM INVERSA) ---".PHP_EOL;

        foreach($this->stepsConcluidos as $step){
            try {
                
                $step->compensar($context);
                $this->registrarEvento($step->getNome(), 'COMPENSACAO', 'Revertido');

            } catch (\Throwable $th) {
                throw new SagaCompensacaoException("Erro crítico na compensação do passo '{$step->getNome()}': " . $e->getMessage());
            }
        }

        $this->estado = EstadoSaga::COMPENSADO;
        echo PHP_EOL."--- COMPENSAÇÃO DE SAGA CONCLUÍDA COM SUCESSO ---\n".PHP_EOL;
    }
}