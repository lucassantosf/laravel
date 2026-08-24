<?php 

declare(strict_types=1);

namespace Desafio05\Interfaces;
use Desafio05\JobAbstract;

interface JobMiddlewareInterface {
    public function processar(JobAbstract $job, callable $next): void;
}