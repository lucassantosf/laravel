<?php 

declare(strict_types=1);

namespace Desafio08\Handlers;

use Desafio08\Evento;
use Desafio08\Interfaces\HandlerEventoInterface;
use Desafio08\Exceptions\EventoFalhouException;

class AuditoriaGeralHandler implements HandlerEventoInterface{

    private static array $historico = [];

    public function getNome(): string{
        return "Auditoria Geral";
    }

    public function getTopicoDeInteresse(): string{
        return "order.*";
    }

    public function getPrioridade(): int{
        return 9;
    }

    public function processar(Evento $evento): bool{
        self::$historico[] = $evento;
        $n = count(self::$historico);
        echo PHP_EOL."[AUDITORIA] Evento {$evento->getId()} do tópico {$evento->getTopico()} registrado. Total: {$n}.".PHP_EOL;
        return true;
    }

    public static function getHistorico(): array{
        return self::$historico;
    }

}    