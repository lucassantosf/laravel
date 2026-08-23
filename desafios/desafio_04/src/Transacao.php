<?php 

declare(strict_types=1);

namespace Desafio04;

use Desafio04\Enums\MetodoPagamento;

class Transacao{

    private string $id;
    private float $valor;
    private MetodoPagamento $metodo;
    private bool $simularFalha = false;
    private string $status = 'PENDENTE';

    public function __construct(string $id, float $valor, MetodoPagamento $metodo, bool $simularFalha){
        $this->id = $id;
        $this->valor = $valor;
        $this->metodo = $metodo;
        $this->simularFalha = $simularFalha;
    }

    public function getId():string{
        return $this->id;
    }

    public function getValor():float{
        return $this->valor;
    }

    public function getMetodo():MetodoPagamento{
        return $this->metodo;
    }

    public function getSimularFalha():bool{
        return $this->simularFalha;
    }

    public function getStatus():bool{
        return $this->status;
    }

    public function setValor(float $valor):void{
        $this->valor = $valor;
    }

    public function setStatus(string $status):void{
        $this->status = $status;
    }

} 