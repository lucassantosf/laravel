<?php 

declare(strict_types=1);

namespace Desafio04\Interfaces;

use Desafio04\Transacao;

interface GatewayPagamentoInterface {
    public function processar(Transacao $transacao): bool;
    public function getNomeGateway(): string;
} 