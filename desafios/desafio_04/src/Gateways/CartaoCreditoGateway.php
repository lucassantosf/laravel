<?php 

declare(strict_types=1);

namespace Desafio04\Gateways;

use Desafio04\Interfaces\GatewayPagamentoInterface;
use Desafio04\Enums\MetodoPagamento;
use Desafio04\Exceptions\GatewayIndisponivelException;
use Desafio04\Exceptions\SaldoInsuficienteException;
use Desafio04\Transacao;

class CartaoCreditoGateway implements GatewayPagamentoInterface{

    private string $nome;
    private bool $isPrincipal;

    public function __construct(string $nome,bool $isPrincipal){
        $this->nome = $nome;
        $this->isPrincipal = $isPrincipal;
    }

    public function processar(Transacao $transacao): bool{
        if($transacao->getSimularFalha() && $this->isPrincipal){
            throw new GatewayIndisponivelException("Falha de conexão com o " . $this->nome, 1);
        }

        if($transacao->getValor() > 5000){
            throw new SaldoInsuficienteException("Limite de crédito excedido para valor R$ " . $transacao->getValor());
        }

        $transacao->setStatus("APROVADO");

        return true;
    }

    public function getNomeGateway(): string{
        return $this->nome;
    }

}  