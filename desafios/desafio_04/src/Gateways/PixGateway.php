<?php 

declare(strict_types=1);

namespace Desafio04\Gateways;

use Desafio04\Interfaces\GatewayPagamentoInterface;
use Desafio04\Enums\MetodoPagamento;
use Desafio04\Exceptions\GatewayIndisponivelException;
use Desafio04\Transacao;

class PixGateway implements GatewayPagamentoInterface{

    public function processar(Transacao $transacao): bool{
        if($transacao->getMetodo() != MetodoPagamento::PIX){
            throw new GatewayIndisponivelException("Este gateway processa apenas PIX.");            
        }

        $oldValor = $transacao->getValor();
        $novoValor = $oldValor - ($oldValor * 0.05);

        $transacao->setValor($novoValor);
        $transacao->setStatus('APROVADO');

        return true;
    }

    public function getNomeGateway(): string{
        return "Gateway PIX";
    }

} 