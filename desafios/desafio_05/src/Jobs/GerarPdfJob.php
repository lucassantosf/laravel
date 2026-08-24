<?php 

declare(strict_types=1);

namespace Desafio05\Jobs;

use Desafio05\JobAbstract;
use Desafio05\Exceptions\JobFalhouException;

class GerarPdfJob extends JobAbstract{
    protected string $nomeArquivo;
    protected string $conteudo;

    public function executar(): bool{
        if(strlen($this->conteudo)<5)
            throw new JobFalhouException("Conteúdo muito curto para gerar PDF.", 1);

        echo PHP_EOL.PHP_EOL."[PDF] Arquivo {$this->nomeArquivo} gerado com sucesso.";
        return true;            
    }

    public function setNomeArquivo(string $nomeArquivo):void{
        $this->nomeArquivo = $nomeArquivo;
    }

    public function setConteudo(string $conteudo):void{
        $this->conteudo = $conteudo;
    }
} 