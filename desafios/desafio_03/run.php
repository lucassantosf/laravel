<?php 

declare(strict_types=1);

spl_autoload_register(function ($class) {
    $prefix = 'Desafio03\\';
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

use Desafio03\GerenciadorNotificacoes;
use Desafio03\Canals\CanalEmail;
use Desafio03\Canals\CanalSMS;
use Desafio03\Mensagem;

echo PHP_EOL . "========================================" . PHP_EOL;
echo "  DESAFIO 03: GERENCIADOR DE NOTIFICAÇÕES" . PHP_EOL;
echo "========================================" . PHP_EOL . PHP_EOL;

echo ">>> Instanciando e registrando canais..." . PHP_EOL;
$gerenciador = new GerenciadorNotificacoes();
$canalEmail = new CanalEmail();
$canalSMS = new CanalSMS();
$gerenciador->registrarCanal($canalEmail);
$gerenciador->registrarCanal($canalSMS);

echo PHP_EOL . ">>> Simulando disparos direcionados por canal..." . PHP_EOL;

// Mensagem 1: Destinada ao canal E-mail
$mensagemEmail = new Mensagem("lucas@exemplo.com", "Alerta", "Seu código de acesso é 1234", "E-mail");

// Mensagem 2: Destinada ao canal SMS (curta)
$mensagemSMS = new Mensagem("5515911223344", "Alerta", "Seu código de acesso é 1234", "SMS");

// Mensagem 3: Destinada ao canal SMS (longa - testando truncamento)
$mensagemSMS2 = new Mensagem(
    "5515955667788",
    "Alerta",
    "Seu código de acesso é 1234. Este é um texto muito longo que ultrapassa o limite padrão de 160 caracteres estabelecido para o envio de mensagens de SMS curtas.",
    "SMS"
);

$gerenciador->disparar($mensagemEmail);
$gerenciador->disparar($mensagemSMS);
$gerenciador->disparar($mensagemSMS2);

echo PHP_EOL . ">>> Simulando validação de erro de e-mail inválido..." . PHP_EOL;

// Mensagem 4: E-mail inválido (sem @) destinada ao canal E-mail
$mensagemInvalida = new Mensagem("lucas_sem_arroba", "Alerta", "Seu código de acesso é 1234", "E-mail");
$gerenciador->disparar($mensagemInvalida);

echo PHP_EOL . ">>> Imprimindo histórico de logs de auditoria..." . PHP_EOL . PHP_EOL;

$logs = $gerenciador->getHistoricoLogs();

foreach ($logs as $log) {
    echo $log . PHP_EOL;
}

echo PHP_EOL . ">>> Script finalizado com sucesso! <<<" . PHP_EOL . PHP_EOL;