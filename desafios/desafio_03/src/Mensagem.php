<?php 

declare(strict_types=1);

namespace Desafio03;

use Desafio03\Exceptions\DestinatarioInvalidoException;

class Mensagem {

    private string $destinatario;
    private string $titulo;
    private string $conteudo;
    private string $canalDesejado;

    public function __construct(string $destinatario, string $titulo, string $conteudo, string $canalDesejado) {
        $this->destinatario = trim($destinatario);
        $this->titulo = $titulo;
        $this->conteudo = $conteudo;
        $this->canalDesejado = $canalDesejado;

        if (empty($this->destinatario)) {
            throw new DestinatarioInvalidoException("O destinatário não pode ser vazio.", 1);
        }
    }

    public function getDestinatario(): string {
        return $this->destinatario;
    }

    public function getTitulo(): string {       
        return $this->titulo;
    }

    public function getConteudo(): string {
        return $this->conteudo;
    }

    public function getCanalDesejado(): string {
        return $this->canalDesejado;
    }

}