<?php 

declare(strict_types=1);

namespace Desafio03\Canals;

use Desafio03\Exceptions\DestinatarioInvalidoException;
use Desafio03\Interfaces\CanalEnvioInterface;
use Desafio03\Mensagem;

class CanalSMS implements CanalEnvioInterface {

    public function enviar(Mensagem $mensagem): bool {
        $destinatario = $mensagem->getDestinatario();

        if (str_contains($destinatario, "@")) {
            throw new DestinatarioInvalidoException("Número de telefone inválido para SMS: " . $destinatario);
        }

        $conteudo = $mensagem->getConteudo();

        if (strlen($conteudo) > 160) {
            $sub = substr($conteudo, 0, 157); 
            $conteudo = "{$sub}...";
        }

        echo "[{$this->getNomeCanal()}] Enviando para {$destinatario} : {$conteudo}" . PHP_EOL;

        return true;
    }

    public function getNomeCanal(): string {
        return "SMS";
    }
}