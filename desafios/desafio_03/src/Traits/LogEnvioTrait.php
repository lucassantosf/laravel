<?php 

declare(strict_types=1);

namespace Desafio03\Traits;

trait LogEnvioTrait{
    private array $historicoLogs = [];

    public function registrarLog(string $canal, string $destinatario, string $status): void{
        $log = [
            "canal"=>$canal, 
            "destinatario"=>$destinatario, 
            "status"=>$status, 
            "timestamp"=>date('Y-m-d H:i:s'), 
        ];
        $this->historicoLogs[] = json_encode($log);
    }

    public function getHistoricoLogs(): array{
        return $this->historicoLogs;
    }
} 