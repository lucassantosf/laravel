<?php 

declare(strict_types=1);

namespace Desafio02;

use Desafio02\Veiculo;

class Locadora {

    private $veiculos = [];

    public function adicionarVeiculo(Veiculo $veiculo): void{
        $this->veiculos[] = $veiculo;
    }

    public function buscarPorPlaca(string $placa): Veiculo{
        foreach ($this->veiculos as $veiculo) {
            if (strcasecmp($veiculo->getPlaca(), $placa) === 0) {
                return $veiculo;
            }
        }
        return null;
    }

    public function listarDisponiveis(): array{
        return array_values(array_filter(
            $this->veiculos,
            fn($veiculo) => $veiculo->isDisponivel()
        ));
    }

}