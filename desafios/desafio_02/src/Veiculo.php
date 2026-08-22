<?php 

declare(strict_types=1);

namespace Desafio02;

use Desafio02\Exceptions\VeiculoIndisponivelException;

abstract class Veiculo {

    protected $modelo;
    protected $placa;
    protected $precoDiaria;
    protected $disponivel = true;

    public function __construct(string $modelo, string $placa, float $precoDiaria){
        $this->modelo = $modelo;
        $this->placa = strtoupper($placa);
        $this->precoDiaria = $precoDiaria;
    }

    public function getModelo(){
        return $this->modelo;
    }

    public function getPlaca(){
        return $this->placa;
    }

    public function getPrecoDiaria(){
        return $this->precoDiaria;
    }

    public function isDisponivel(): bool{
        return $this->disponivel;
    }

    abstract public function calcularValorAluguel(int $dias): float;

    public function alugar(): void{
        if(!$this->isDisponivel())
            throw new VeiculoIndisponivelException("Veiculo indisponivel", 1);
            
        $this->disponivel = false;
    }

    public function devolver(): void{
        $this->disponivel = true;
    }

}