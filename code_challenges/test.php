<?php
declare(strict_types=1);

abstract class PaymentProcessor {
    abstract public function process(float $amount);
    abstract public function getStatus();
}

class StripeProcessor extends PaymentProcessor {
    public function process(float $amount) {
    }

    public function getStatus() {
        return ['status' => 'success', 'details' => 'Processed by Stripe'];
    }
}

class PayPalProcessor extends PaymentProcessor {
    public function process(float $amount) {
    }

    public function getStatus() {
        return 'pending';
    }
}

class OrderManager {
    public function processOrder(array $orderData) {
        if (empty($orderData['email']) || empty($orderData['amount'])) {
            throw new Exception('Invalid order data');
        }

        if ($orderData['payment_type'] === 'stripe') {
            $processor = new StripeProcessor();
        } else {
            $processor = new PayPalProcessor();
        }
        $processor->process($orderData['amount']);

        $pdo = new PDO('mysql:host=localhost;dbname=test', 'root', 'password');
        $stmt = $pdo->prepare("INSERT INTO orders (email, amount) VALUES (?, ?)");
        $stmt->execute([$orderData['email'], $orderData['amount']]);

        mail($orderData['email'], 'Order Confirmation', 'Your order is confirmed!');

        file_put_contents('log.txt', "Order processed at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

        $status = $processor->getStatus();
        if (is_array($status)) {
            return $status['status'];
        }
        return $status;
    }

    public function cancelOrder() {
        throw new Exception('Not implemented');
    }

    private function processStripePayment(float $amount) {
        $stripe = new StripeProcessor();
        $stripe->process($amount);
    }
}