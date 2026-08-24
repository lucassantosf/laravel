<?php 

declare(strict_types=1);

namespace Desafio05\Enums;

enum StatusJob: string{
    case PENDENTE = 'PENDENTE';
    case PROCESSANDO = 'PROCESSANDO';
    case CONCLUIDO = 'CONCLUIDO';
    case FALHOU = 'FALHOU';
} 