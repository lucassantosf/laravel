<?php 

declare(strict_types=1);

namespace Desafio07\Traits;

trait AuditoriaSagaTrait{

    private array $trilhaAuditoria = [];

    public function registrarEvento(string $stepNome, string $acao = 'EXECUCAO' | 'COMPENSACAO', string $detalhe = ''): void{
        $this->trilhaAuditoria[] = [
            'stepNome'=>$stepNome,
            'acao'=>$acao,
            'detalhe'=>$detalhe,
        ];
    }

    public function getTrilhaAuditoria(): array{
        return $this->trilhaAuditoria;
    }

} 