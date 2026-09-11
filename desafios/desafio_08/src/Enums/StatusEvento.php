<?php 

declare(strict_types=1);

namespace Desafio08\Enums;

enum StatusEvento:string{
    case PUBLICADO='PUBLICADO';
    case PROCESSANDO='PROCESSANDO';
    case FALHOU='FALHOU';
    case DESCARTADO='DESCARTADO';
}
