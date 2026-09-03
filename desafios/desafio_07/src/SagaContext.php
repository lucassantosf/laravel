<?php 

declare(strict_types=1);

namespace Desafio07;

class SagaContext{
    private array $dados = [];

    public function set(string $chave, mixed $valor): void{
        $this->dados[$chave] = $valor;
    }

    public function get(string $chave, mixed $default = null): mixed{
        return $this->dados[$chave] ?? $default;
    }

    public function has(string $chave): bool{
        return array_key_exists($chave, $this->dados);
    }

    public function all(): array{
        return $this->dados;
    }
} 