<?php 

declare(strict_types=1);

namespace Desafio08\Outbox;

use Desafio08\Outbox\OutboxMessage;

class OutboxStore{

    private array $mensagens = [];

    public function adicionar(OutboxMessage $mensagem): void
    {
        $this->mensagens[$mensagem->getEvento()->getId()] = $mensagem;
    }

    public function marcarComoEnviado(string $eventoId): void
    {
        if (isset($this->mensagens[$eventoId])) {
            $this->mensagens[$eventoId]->setEstado('ENVIADO');
        }
    }

    public function marcarComoFalhou(string $eventoId): void
    {
        if (isset($this->mensagens[$eventoId])) {
            $this->mensagens[$eventoId]->setEstado('FALHOU');
        }
    }

    public function getPendentes(): array
    {
        return array_values(
            array_filter(
                $this->mensagens,
                fn(OutboxMessage $msg) => $msg->getEstado() === 'PENDENTE'
            )
        );
    }

    public function getTodas(): array
    {
        return array_values($this->mensagens);
    }

}