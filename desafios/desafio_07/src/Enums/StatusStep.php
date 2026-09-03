<?php

declare(strict_types=1);

namespace Desafio07\Enums;

enum StatusStep: string {
    case PENDENTE='PENDENTE';
    case SUCESSO='SUCESSO';
    case FALHOU='FALHOU';
    case COMPENSADO='COMPENSADO';
}