<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    $prefix = 'Desafio07\\';
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

use Desafio07\SagaContext;
use Desafio07\WorkflowBuilder; 
use Desafio07\Steps\ReservarEstoqueStep;
use Desafio07\Steps\ProcessarPagamentoStep;
use Desafio07\Steps\EmitirNotaFiscalStep;
use Desafio07\Steps\EnviarNotificacaoStep;

//   --- CENÁRIO 1: FLUXO FELIZ (SUCESSO TOTAL) ---

$context1 = new SagaContext();

$context1->set('valor_pedido',150);

$flow1 = WorkflowBuilder::criar();

$step1 = new ReservarEstoqueStep();
$step2 = new ProcessarPagamentoStep(10);
$step3 = new EmitirNotaFiscalStep();
$step4 = new EnviarNotificacaoStep();

$flow1->addStep($step1);
$flow1->addStep($step2);
$flow1->addStep($step3);
$flow1->addStep($step4);

$orchestrator1 = $flow1->build(); 
$orchestrator1->run($context1);

$dados1 = $context1->all();
$estado1 = $context1->get("estado");

echo PHP_EOL." Estado Saga 1 {$estado1} :".PHP_EOL;

foreach($dados1 as $key=>$dado){
    echo PHP_EOL." dado key {$key} :".PHP_EOL;
    var_dump($dado);
    echo PHP_EOL;
} 
//   --- FIM CENÁRIO 1 ---

//   --- CENÁRIO 2: FLUXO DE FALHA E ROLLBACK COMPENSATÓRIO ---

$context2 = new SagaContext();

$context2->set('valor_pedido',150);
$context2->set('simular_falha_fiscal',true);

$flow2 = WorkflowBuilder::criar();

$step5 = new ReservarEstoqueStep();
$step6 = new ProcessarPagamentoStep(10);
$step7 = new EmitirNotaFiscalStep();
$step8 = new EnviarNotificacaoStep();

$flow2->addStep($step5);
$flow2->addStep($step6);
$flow2->addStep($step7);
$flow2->addStep($step8);

$orchestrator2 = $flow2->build(); 
$orchestrator2->run($context2);

$dados2 = $context2->all();
$estado2 = $context2->get("estado");

echo PHP_EOL." Estado Saga 2 {$estado2} :".PHP_EOL;

foreach($dados2 as $key=>$dado){
    echo PHP_EOL." dado key {$key} :".PHP_EOL;
    var_dump($dado);
    echo PHP_EOL;
}  

$report = $orchestrator2->getTrilhaAuditoria();

foreach ($report as $key => $r) {
    echo PHP_EOL." report key {$key} :".PHP_EOL;
    var_dump($r);
    echo PHP_EOL;
}
//   --- FIM CENÁRIO 2 ---

print_m(">>> Script finalizado com sucesso! <<<");