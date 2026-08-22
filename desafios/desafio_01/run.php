<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    $prefix = 'Desafio01\\';
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

use Desafio01\PaymentProcessor;
use Desafio01\Gateways\StripeGateway;
use Desafio01\Pipeline\FeePipeline;
use Desafio01\Services\AuditLogger;

echo "========================================\n";
echo "   DESAFIO 02: PIPELINE DE PAGAMENTOS   \n";
echo "========================================\n\n";

// Configuração da Pipeline de Taxas
$feePipeline = new FeePipeline();
// Estágio 1: Taxa fixa de gateway (+ R$ 2.00)
$feePipeline->addStage(fn(float $amount): float => $amount + 2.00);
// Estágio 2: Taxa percentual de processamento (+ 5%)
$feePipeline->addStage(fn(float $amount): float => $amount * 1.05);

// ----------------------------------------------------
// BATCH 1: Processando lote normal (2 transações com sucesso)
// ----------------------------------------------------
echo ">>> Processando Batch 1 (Sucesso)...\n";
$gatewayNormal = new StripeGateway(shouldFail: false);
$processor1 = new PaymentProcessor($gatewayNormal, $feePipeline);

$batch1Transactions = [
    ['id' => 'TX_101', 'amount' => 100.00, 'card' => '4111****1111'],
    ['id' => 'TX_102', 'amount' => 200.00, 'card' => '4222****2222'],
];

$res1 = $processor1->processBatch($batch1Transactions);
$logsBatch1Count = AuditLogger::getLogCount();

echo "Transações processadas no Batch 1: " . count($res1) . "\n";
echo "Logs de auditoria gerados no Batch 1: " . $logsBatch1Count . "\n";

// ----------------------------------------------------
// BATCH 2: Processando lote com falha no Gateway
// ----------------------------------------------------
echo "\n>>> Processando Batch 2 (Com falha de gateway)...\n";
$gatewayFailing = new StripeGateway(shouldFail: true);
$processor2 = new PaymentProcessor($gatewayFailing, $feePipeline);

$batch2Transactions = [
    ['id' => 'TX_201', 'amount' => 50.00, 'card' => '4333****3333'],
];

$res2 = $processor2->processBatch($batch2Transactions);
$logsBatch2Count = AuditLogger::getLogCount();

echo "Transações processadas no Batch 2: " . count($res2) . "\n";
echo "Logs de auditoria gerados no Batch 2: " . $logsBatch2Count . "\n";

// ----------------------------------------------------
// VERIFICAÇÕES DE RESULTADOS
// ----------------------------------------------------
echo "\n--- VERIFICAÇÃO DOS RESULTADOS ---\n";
$errors = [];

// Cálculo esperado TX_101: (100 + 2) * 1.05 = 107.10
if (!isset($res1[0]['amount']) || abs($res1[0]['amount'] - 107.10) > 0.01) {
    $val = isset($res1[0]['amount']) ? number_format($res1[0]['amount'], 2) : 'NULL/inválido';
    $errors[] = "FAIL: Cálculo de taxa incorreto em TX_101. Esperado R$ 107.10, obtido R$ {$val}";
}

// O Batch 1 tem 2 transações -> 2 logs de inicio + 2 logs de aprovacao = 4 logs.
if ($logsBatch1Count !== 4) {
    $errors[] = "FAIL: Contagem de logs do Batch 1 incorreta. Esperado 4 logs, obtido {$logsBatch1Count}.";
}

// O Batch 2 tem 1 transação rejeitada -> 1 log de inicio + 1 log de rejeicao = 2 logs.
if ($logsBatch2Count !== 2) {
    $errors[] = "FAIL: Contagem de logs do Batch 2 incorreta. Esperado 2 logs isolados, obtido {$logsBatch2Count}.";
}

if (!isset($res2[0]['status']) || $res2[0]['status'] !== 'REJECTED') {
    $errors[] = "FAIL: Transação do Batch 2 deveria ter sido REJECTED devido à falha no gateway.";
}

if (empty($errors)) {
    echo "\n🎉 PARABÉNS! Você superou o Desafio 02 com maestria!\n\n";
} else {
    echo "\n❌ ERROS ENCONTRADOS:\n";
    foreach ($errors as $error) {
        echo "   - $error\n";
    }
    echo "\n";
}
