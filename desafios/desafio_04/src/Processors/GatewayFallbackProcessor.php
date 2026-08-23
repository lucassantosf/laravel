<?php 

declare(strict_types=1);

namespace Desafio04\Processors;

use Desafio04\Interfaces\GatewayPagamentoInterface;
use Desafio04\Exceptions\GatewayIndisponivelException;
use Desafio04\Exceptions\SaldoInsuficienteException;
use Desafio04\Transacao;

class GatewayFallbackProcessor {

    private GatewayPagamentoInterface $gatewayPrincipal;
    private GatewayPagamentoInterface $gatewaySecundario;

    public function __construct(GatewayPagamentoInterface $gatewayPrincipal,GatewayPagamentoInterface $gatewaySecundario){
        $this->gatewayPrincipal = $gatewayPrincipal;
        $this->gatewaySecundario = $gatewaySecundario;
    }

    public function processarComFallback(Transacao $transacao): bool{
        try {

            $this->gatewayPrincipal->processar($transacao);
            return true;
        
        } catch (GatewayIndisponivelException $e) {
            echo PHP_EOL.PHP_EOL."[AVISO] Gateway Principal indisponível! Acionando Fallback para {$this->gatewaySecundario->getNomeGateway()}...".PHP_EOL.PHP_EOL;
            $this->gatewaySecundario->processar($transacao);
            return false;
        }
    }

} 