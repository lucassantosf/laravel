<?php 

declare(strict_types=1);

namespace Desafio08\Enums;

enum PrioridadeEvento:int{
    case ALTA=1;
    case MEDIA=2;
    case BAIXA=3;

    public function obterOrdem(): int
    {
        return $this->value;
    }
}
