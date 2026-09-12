<?php 

declare(strict_types=1);

namespace Desafio08\Interfaces;

use Desafio08\Evento;

interface HandlerEventoInterface{

    public function getNome(): string;
    public function getTopicoDeInteresse(): string;
    public function getPrioridade(): int;
    public function processar(Evento $evento): bool;

}