<?php 

declare(strict_types=1);

namespace Desafio02;

use Desafio02\Veiculo;
use Desafio02\Interfaces\Manutenivel;

class Carro extends Veiculo implements Manutenivel{

    private $quantidadePortas;

    public function __construct(string $modelo, string $placa, float $precoDiaria, int $quantidadePortas){
        parent::__construct($modelo,$placa,$precoDiaria);
        $this->quantidadePortas = $quantidadePortas;
    }

    public function getQuantidadePortas(): int{
        return $this->quantidadePortas;
    }

    public function calcularValorAluguel(int $dias): float{
        $valor = $dias * $this->getPrecoDiaria();

        if($this->getQuantidadePortas() > 4)
            return $valor + 20;

        return $valor;
    }

    public function realizarManutencao(): string{
        return "Manutenção de rotina realizada no carro modelo {$this->getModelo()}.";
    }

}
