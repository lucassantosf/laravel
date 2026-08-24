<?php 

declare(strict_types=1);

namespace Desafio05\Traits;

trait EstatisticasFilaTrait{
    protected int $jobsConcluidos = 0;
    protected int $jobsRetriados = 0;
    protected int $jobsDeadLetter = 0;

    public function incrementarConcluido(){
        $this->jobsConcluidos++;
    }

    public function incrementarRetry(){
        $this->jobsRetriados++;
    }

    public function incrementarDeadLetter(){
        $this->jobsDeadLetter++;
    }

    public function getResumoEstatisticas(): array{
        return [
            'jobsConcluidos'=>$this->jobsConcluidos,
            'jobsRetriados'=>$this->jobsRetriados,
            'jobsDeadLetter'=>$this->jobsDeadLetter,
        ];
    }
} 