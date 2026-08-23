<?php 

declare(strict_types=1);

namespace Desafio04\Enums;

enum MetodoPagamento {
    case PIX;
    case CARTAO_CREDITO;
    case BOLETO;

    public function obterTaxaServico(): float{
        return match($this){
            self::PIX=>0.0,
            self::CARTAO_CREDITO=>0.03,
            self::BOLETO=>2.5,
        };
    }
}; 