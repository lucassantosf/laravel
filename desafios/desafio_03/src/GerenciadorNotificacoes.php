<?php 

declare(strict_types=1);

namespace Desafio03;

use Desafio03\Interfaces\CanalEnvioInterface;
use Desafio03\Traits\LogEnvioTrait;
use Desafio03\Exceptions\DestinatarioInvalidoException;
use Desafio03\Mensagem;

class GerenciadorNotificacoes {

    use LogEnvioTrait;

    protected array $canais = [];

    public function registrarCanal(CanalEnvioInterface $canal): void {
        $this->canais[] = $canal;
    }

    public function disparar(Mensagem $mensagem): void {
        foreach ($this->canais as $canal) {
            // Dispara APENAS se o nome do canal for igual ao canal desejado da mensagem
            if (strcasecmp($canal->getNomeCanal(), $mensagem->getCanalDesejado()) === 0) {
                try {
                    $canal->enviar($mensagem);
                    $this->registrarLog($canal->getNomeCanal(), $mensagem->getDestinatario(), 'SUCESSO');
                } catch (DestinatarioInvalidoException $e) {
                    echo "Falha no envio via {$canal->getNomeCanal()}: {$e->getMessage()}" . PHP_EOL;
                    $this->registrarLog($canal->getNomeCanal(), $mensagem->getDestinatario(), 'FALHA');
                }
            }
        }
    }
}