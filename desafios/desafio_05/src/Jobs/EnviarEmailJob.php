<?php 

declare(strict_types=1);

namespace Desafio05\Jobs;

use Desafio05\JobAbstract;
use Desafio05\Exceptions\JobFalhouException;

class EnviarEmailJob extends JobAbstract{
    protected string $destinatario;
    protected string $corpo;

    public function executar(): bool{

        if(!str_contains($this->destinatario,'@'))
            throw new JobFalhouException("E-mail inválido: " . $this->destinatario, 1);

        echo PHP_EOL.PHP_EOL."[EMAIL] Enviado para {$this->destinatario}";
        return true;
    }

    public function setDestinatario(string $destinatario):void{
        $this->destinatario = $destinatario;
    }

    public function setCorpo(string $corpo):void{
        $this->corpo = $corpo;
    }
}