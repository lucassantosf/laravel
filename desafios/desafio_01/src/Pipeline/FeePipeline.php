<?php

declare(strict_types=1);

namespace Desafio01\Pipeline;

class FeePipeline {
    private array $stages = [];

    public function addStage(callable $stage): self {
        $this->stages[] = $stage;
        return $this;
    }

    public function process(float $amount): float {
        return array_reduce($this->stages, function (float $carry, callable $stage): float {
            return $stage($carry); 
        }, $amount);
    }
}
