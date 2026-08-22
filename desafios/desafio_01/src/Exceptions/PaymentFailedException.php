<?php

declare(strict_types=1);

namespace Desafio01\Exceptions;

class PaymentFailedException implements Throwable {
    protected string $message;
    protected int $code;

    public function __construct(string $message, int $code = 0) {
        $this->message = $message;
        $this->code = $code;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getCode(): int {
        return $this->code;
    }
}
