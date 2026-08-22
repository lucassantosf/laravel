<?php

declare(strict_types=1);

namespace Desafio01;

use Desafio01\Gateways\PaymentGatewayInterface;
use Desafio01\Pipeline\FeePipeline;
use Desafio01\Services\AuditLogger;
use Desafio01\Exceptions\PaymentFailedException;

class PaymentProcessor {
    private PaymentGatewayInterface $gateway;
    private FeePipeline $feePipeline;

    public function __construct(PaymentGatewayInterface $gateway, FeePipeline $feePipeline) {
        $this->gateway = $gateway;
        $this->feePipeline = $feePipeline;
    }

    public function processBatch(array $transactions): array {
        // Antes de cada lote, limpa o log de auditoria do lote anterior
        AuditLogger::reset();

        $results = [];

        foreach ($transactions as $tx) {
            AuditLogger::log("Iniciando transacao ID: " . $tx['id']);

            try {
                $finalAmount = $this->feePipeline->process($tx['amount']);

                $success = $this->gateway->charge($finalAmount, $tx['card']);

                if ($success) {
                    AuditLogger::log("Transacao ID: " . $tx['id'] . " APROVADA no valor R$ " . $finalAmount);
                    $results[] = [
                        'id' => $tx['id'],
                        'status' => 'APPROVED',
                        'amount' => $finalAmount,
                    ];
                }
            } catch (\Throwable $e) {
                // Se a exceção implementa Throwable/Exception ela é capturada aqui
                AuditLogger::log("Transacao ID: " . $tx['id'] . " REJEITADA: " . $e->getMessage());
                $results[] = [
                    'id' => $tx['id'],
                    'status' => 'REJECTED',
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}
