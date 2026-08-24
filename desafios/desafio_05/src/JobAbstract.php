<?php 

declare(strict_types=1);

namespace Desafio05;

use Desafio05\Enums\PrioridadeJob;
use Desafio05\Enums\StatusJob;

abstract class JobAbstract{
    protected string $id;
    protected PrioridadeJob $prioridade;
    protected int $maxTentativas = 3;
    protected int $tentativaAtual = 0;
    protected StatusJob $status = StatusJob::PENDENTE;

    public function __construct(string $id,PrioridadeJob $prioridade,int $maxTentativas){
        $this->id = $id;
        $this->prioridade = $prioridade;
        $this->maxTentativas = $maxTentativas;
    }

    abstract public function executar(): bool;

    public function incrementarTentativa(): void{
        $this->tentativaAtual++;
    }

    public function isMaxTentativasExcedidas(): bool{

        if($this->tentativaAtual>=$this->maxTentativas)
            return true;

        return false;
    }

    public function getId():string{
        return $this->id;
    }

    public function setId(string $id):void{
        $this->id = $id;
    }

    public function getMaxTentativas():int{
        return $this->maxTentativas;
    }

    public function setMaxTentativas(int $maxTentativas):void{
        $this->maxTentativas = $maxTentativas;
    }

    public function getTentativaAtual():int{
        return $this->tentativaAtual;
    }

    public function setTentativaAtual(int $tentativaAtual):void{
        $this->tentativaAtual = $tentativaAtual;
    }

    public function getStatus():StatusJob{
        return $this->status;
    }

    public function setStatus(StatusJob $status):void{
        $this->status = $status;
    }

    public function getPrioridade():PrioridadeJob{  
        return $this->prioridade;
    }
    
    public function setPrioridade(PrioridadeJob $prioridade):void{
        $this->prioridade = $prioridade;
    }
}