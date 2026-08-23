<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    $prefix = 'Desafio04\\';
    $baseDir = __DIR__ . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

function print_m($text){
    echo PHP_EOL . "{$text}" . PHP_EOL . PHP_EOL;
}

use Desafio04\Gateways\PixGateway;
use Desafio04\Gateways\CartaoCreditoGateway;
use Desafio04\Processors\GatewayFallbackProcessor;
use Desafio04\CheckoutProcessor;
use Desafio04\Transacao;
use Desafio04\Enums\MetodoPagamento;
 
$pixGateway = new PixGateway();
$cartaoCreditoGatewayPrincipal = new CartaoCreditoGateway(' - principal - ',true);
$cartaoCreditoGatewaySecundario = new CartaoCreditoGateway(' - secundario - ',false);
$fallbackProcessor = new GatewayFallbackProcessor($cartaoCreditoGatewayPrincipal,$cartaoCreditoGatewaySecundario);
$processor = new CheckoutProcessor($fallbackProcessor,$pixGateway);

//     1. Transação PIX (R$ 100.00) -> deve receber 5% de desconto e aprovar.
$transacao1 = new Transacao('1',100.00,MetodoPagamento::PIX,false);
$processor->processarCheckout($transacao1);

//     2. Transação Cartão Normal (R$ 250.00) -> deve aprovar no principal.
$transacao2 = new Transacao('2',250.00,MetodoPagamento::CARTAO_CREDITO,false);
$processor->processarCheckout($transacao2);

//     3. Transação Cartão com Falha Simulada (R$ 300.00, `simularFalha = true`) -> deve falhar no principal e ser APROVADA no gateway secundário via Fallback!
$transacao3 = new Transacao('3',300.00,MetodoPagamento::CARTAO_CREDITO,true);
$processor->processarCheckout($transacao3);

//     4. Transação Cartão com valor alto (R$ 6000.00) -> deve ser RECUSADA por limite excedido.
$transacao4 = new Transacao('4',6000.00,MetodoPagamento::CARTAO_CREDITO,true);
$processor->processarCheckout($transacao4);

print_m(">>> Métricas financeiras <<<");

$report = $processor->getResumoMetricas();
echo json_encode($report);

print_m(">>> Script finalizado com sucesso! <<<");
 