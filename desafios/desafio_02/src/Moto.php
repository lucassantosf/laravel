<?php 

declare(strict_types=1);

namespace Desafio02;

use Desafio02\Veiculo;

class Moto extends Veiculo{

    private $cilindradas; 

    public function __construct(string $modelo, string $placa, float $precoDiaria, int $cilindradas){
        parent::__construct($modelo,$placa,$precoDiaria);
        $this->cilindradas = $cilindradas;
    }

    public function getCilindradas(): int{
        return $this->cilindradas;
    }

    public function calcularValorAluguel(int $dias): float{
        $valorDiaria = $this->getPrecoDiaria();

        if($this->getCilindradas() > 200)
            $valorDiaria = $valorDiaria * 1.1;

        return $dias * $valorDiaria;
    }

}