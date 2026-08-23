<?php 

declare(strict_types=1);

namespace Desafio04\Traits;

use Desafio04\Transacao;

trait MetricasPagamentoTrait {

    private float $totalAprovado = 0.0;
    private float $totalRecusado = 0.0;
    private int $qtdAprovadas = 0;
    private int $qtdRecusadas = 0;
    
    public function registrarSucesso(float $valor): void{
        $this->totalAprovado += $valor;
        $this->qtdAprovadas++;
    }

    public function registrarRecusa(float $valor): void{
        $this->totalRecusado += $valor;
        $this->qtdRecusadas++;
    }

    public function getResumoMetricas(): array{
        return [
            'totalAprovado'=>$this->totalAprovado,
            'totalRecusado'=>$this->totalRecusado,
            'qtdAprovadas'=>$this->qtdAprovadas,
            'qtdRecusadas'=>$this->qtdRecusadas,
        ];
    }
} 