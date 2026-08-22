<?php

declare(strict_types=1);

namespace Desafio01\Gateways;

interface PaymentGatewayInterface {
    public function charge(float $amount, string $cardNumber): bool;
}
