<?php 

declare(strict_types=1);

namespace Desafio03\Interfaces;

use Desafio03\Mensagem;

interface CanalEnvioInterface{
    public function enviar(Mensagem $mensagem): bool;
    public function getNomeCanal(): string;
}