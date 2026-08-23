<?php 

declare(strict_types=1);

namespace Desafio04;

use Desafio04\Traits\MetricasPagamentoTrait;
use Desafio04\Processors\GatewayFallbackProcessor;
use Desafio04\Gateways\PixGateway;
use Desafio04\Exceptions\GatewayIndisponivelException;
use Desafio04\Exceptions\SaldoInsuficienteException;
use Desafio04\Transacao;
use Desafio04\Enums\MetodoPagamento;

class CheckoutProcessor{

    use MetricasPagamentoTrait;

    private GatewayFallbackProcessor $cartaoProcessor;
    private PixGateway $pixGateway;

    public function __construct(GatewayFallbackProcessor $cartaoProcessor,PixGateway $pixGateway){
        $this->cartaoProcessor = $cartaoProcessor;
        $this->pixGateway = $pixGateway;
    }

    public function processarCheckout(Transacao $transacao): void{

        $taxa = $transacao->getMetodo()->obterTaxaServico();
        $novoValor = ($transacao->getValor() * $taxa) + $transacao->getValor();
        $transacao->setValor($novoValor);

        try {

            if($transacao->getMetodo() == MetodoPagamento::PIX){
                $this->pixGateway->processar($transacao);
            }else{
               $this->cartaoProcessor->processarComFallback($transacao);
            }

            echo PHP_EOL.PHP_EOL . "<<< APROVADO COM SUCESSO >>>" . PHP_EOL . PHP_EOL;
            $this->registrarSucesso($transacao->getValor());

        } catch (SaldoInsuficienteException | GatewayIndisponivelException $e) {
           
            $transacao->setStatus("RECUSADO");
            echo PHP_EOL.PHP_EOL . "<<< ERRO: {$e->getMessage()} >>>" . PHP_EOL . PHP_EOL;
            $this->registrarRecusa($transacao->getValor());
            
        }

    }

}