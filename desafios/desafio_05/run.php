<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    $prefix = 'Desafio05\\';
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


use Desafio05\QueueWorker;
use Desafio05\Enums\PrioridadeJob;
use Desafio05\Jobs\EnviarEmailJob;
use Desafio05\Jobs\GerarPdfJob;

$queue_worker = new QueueWorker();

$email_job = new EnviarEmailJob('id01',PrioridadeJob::MEDIA,1);
$email_job->setDestinatario('lucas@test.com');
$email_job->setCorpo('<content></content>');
$queue_worker->push($email_job);

$pdf_job = new GerarPdfJob('id02',PrioridadeJob::ALTA,1);
$pdf_job->setNomeArquivo('nomeArquivo.pdf');
$pdf_job->setConteudo('<content></content>'); 
$queue_worker->push($pdf_job);
 
$email2_job = new EnviarEmailJob('id03',PrioridadeJob::BAIXA,2);
$email2_job->setDestinatario('lucastest.com');
$email2_job->setCorpo('<content></content>');
$queue_worker->push($email2_job);

$pdf2_job = new GerarPdfJob('id02',PrioridadeJob::MEDIA,2);
$pdf2_job->setNomeArquivo('nomeArquivo.pdf');
$pdf2_job->setConteudo('<c'); 
$queue_worker->push($pdf2_job);

$queue_worker->processarFila();

$jobs_dlq = $queue_worker->getDeadLetterQueue();
print_m(json_encode($jobs_dlq));

$report = $queue_worker->getResumoEstatisticas();
print_m(json_encode($report));

// Passo 10: Script Principal e Relatório de Auditoria (run.php)
//   Crie o arquivo `run.php` com autoloader no namespace `Desafio05`:
//   - Instancie o `QueueWorker` e adicione o `LoggingMiddleware`.
//   - Crie e adicione na fila 4 jobs:
//     1. `EnviarEmailJob` válido (Prioridade `MEDIA`) -> deve aprovar de primeira.
//     2. `GerarPdfJob` válido (Prioridade `ALTA`) -> deve ser processado ANTES do e-mail por causa da prioridade ALTA!
//     3. `EnviarEmailJob` com e-mail sem `@` (Prioridade `BAIXA`, maxTentativas = 2) -> deve falhar, tentar o retry e ir para a Dead Letter Queue após 2 tentativas.
//     4. `GerarPdfJob` com conteúdo de 2 letras (Prioridade `MEDIA`, maxTentativas = 2) -> deve falhar e ir para a Dead Letter Queue.
//   - Chame `processarFila()`.
//   - Imprima os jobs presentes na Dead Letter Queue (`getDeadLetterQueue()`) e o resumo final de estatísticas (`getResumoEstatisticas()`)!







print_m(">>> Script finalizado com sucesso! <<<");
