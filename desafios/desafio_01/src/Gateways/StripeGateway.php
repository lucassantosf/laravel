<?php

declare(strict_types=1);

namespace Desafio01\Gateways;

use Desafio01\Exceptions\PaymentFailedException;

class StripeGateway implements PaymentGatewayInterface {
    private bool $shouldFail;

    public function __construct(bool $shouldFail = false) {
        $this->shouldFail = $shouldFail;
    }

    public function charge(float $amount, string $cardNumber): bool {
        if ($this->shouldFail) {
            // Lança a exceção de falha de pagamento
            throw new PaymentFailedException("Falha na comunicação com a adquirente Stripe.", 500);
        }

        return true;
    }
}
