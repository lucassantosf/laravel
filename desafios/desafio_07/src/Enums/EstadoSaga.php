<?php

declare(strict_types=1);

namespace Desafio07\Enums;

enum EstadoSaga: string {
    case NAO_INICIADO='NAO_INICIADO';
    case EM_EXECUCAO='EM_EXECUCAO';
    case SUCESSO='SUCESSO';
    case FALHOU='FALHOU';
    case EM_COMPENSACAO='EM_COMPENSACAO';
    case COMPENSADO='COMPENSADO';
}