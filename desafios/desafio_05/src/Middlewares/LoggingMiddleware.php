<?php 

declare(strict_types=1);

namespace Desafio05\Middlewares;
use Desafio05\Interfaces\JobMiddlewareInterface;
use Desafio05\JobAbstract;

class LoggingMiddleware implements JobMiddlewareInterface{

    public function processar(JobAbstract $job, callable $next): void{
        echo PHP_EOL.PHP_EOL."[MIDDLEWARE LOG] Iniciando Job ID: {$job->getId()} [Prioridade: {$job->getPrioridade()->name}]...";
        $next($job);
        echo PHP_EOL.PHP_EOL."[MIDDLEWARE LOG] Finalizado Job ID: {$job->getId()} [Status: {$job->getStatus()->name}]";
    }
    
} 