<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    $prefix = 'Desafio08\\';
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

use Desafio08\Outbox\OutboxStore;
use Desafio08\EventBus;
use Desafio08\Handlers\AtualizarEstoqueHandler;
use Desafio08\Handlers\NotificarClienteHandler;
use Desafio08\Handlers\RegistrarFinanceiroHandler;
use Desafio08\Handlers\AuditoriaGeralHandler;
use Desafio08\Evento;
use Desafio08\Enums\PrioridadeEvento;
use Desafio08\Exceptions\SemHandlerException;

$outboxStore = new OutboxStore();
$eventBus = new EventBus($outboxStore,3);

$eventBus->registrarHandler(new AtualizarEstoqueHandler());
$eventBus->registrarHandler(new NotificarClienteHandler());
$eventBus->registrarHandler(new RegistrarFinanceiroHandler());
$eventBus->registrarHandler(new AuditoriaGeralHandler());

//   --- **CENÁRIO 1: EVENTO COM MÚLTIPLOS HANDLERS E WILDCARD** ---
$evento1 = new Evento('1','order.created',['produto' => 'Notebook', 'email' => 'cliente@test.com', 'valor' => 350.00],PrioridadeEvento::ALTA);
$eventBus->publicar($evento1);
$report1 = AuditoriaGeralHandler::getHistorico();
var_dump($report1);

//   --- **CENÁRIO 2: ROTEAMENTO DIRECIONADO** ---
$evento2 = new Evento('2','order.paid',['valor' => 250.00],PrioridadeEvento::MEDIA);
$eventBus->publicar($evento2);

//   --- **CENÁRIO 3: FALHA, RETRY E DEAD LETTER QUEUE** ---
$evento3 = new Evento('3','order.created',['produto' => 'Monitor', 'email' => 'cliente@test.com', 'simular_falha' => true],PrioridadeEvento::ALTA);
$eventBus->publicar($evento3);
$report3 = $eventBus->getDeadLetterQueue();
var_dump($report3);

//   --- **CENÁRIO 4: TÓPICO SEM HANDLER** ---
$evento4 = new Evento('4','user.registered',['produto' => 'Impressora','valor' => 350.00],PrioridadeEvento::BAIXA);
try {
    $eventBus->publicar($evento4);
} catch (SemHandlerException $e) {
    print_m($e->getMessage());
}

$metricas = $eventBus->getResumoMetricas();
var_dump($metricas);

$todas = $outboxStore->getTodas();
var_dump($todas);

print_m(">>> Script finalizado com sucesso! <<<");
