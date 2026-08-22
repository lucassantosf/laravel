<?php 

declare(strict_types=1);

namespace Desafio03\Canals;

use Desafio03\Exceptions\DestinatarioInvalidoException;
use Desafio03\Interfaces\CanalEnvioInterface;
use Desafio03\Mensagem;

class CanalEmail implements CanalEnvioInterface {

    public function enviar(Mensagem $mensagem): bool {
        $destinatario = $mensagem->getDestinatario();

        if (!str_contains($destinatario, "@")) {
            throw new DestinatarioInvalidoException("Endereço de e-mail inválido: " . $destinatario);
        }

        echo "[{$this->getNomeCanal()}] Enviando '{$mensagem->getTitulo()}' para {$destinatario}..." . PHP_EOL;

        return true;
    }

    public function getNomeCanal(): string {
        return "E-mail";
    }

}