<?php 

declare(strict_types=1);

namespace Desafio08\Enums;

enum StatusEvento:string{
    case PUBLICADO='PUBLICADO';
    case PROCESSANDO='PROCESSANDO';
    case PROCESSADO='PROCESSADO';
    case FALHOU='FALHOU';
    case DESCARTADO='DESCARTADO';
}
