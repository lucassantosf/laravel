<?php 

/*

Done at: 2026-02-08
Challenge 5 – Rules Engine (Configurable Discount Rules)

Objective:
Refactor the discount system to support dynamic and configurable
discount rules without changing PHP classes.

Description:
The system must allow discount rules to be defined using configuration
(e.g., arrays or JSON-like structures) instead of hardcoded classes.

Each rule must contain:
- A condition: determines whether the rule applies
- An action: determines how the discount is calculated

The rules engine must:
- Evaluate all configured rules for a given order
- Apply ONLY the best discount (lowest final total)
- Be easily extensible by adding new rules via configuration

Example rules:
- 10% off if subtotal > 100
- $15 off if user is premium
- $20 off if order has more than 10 items
- $5 off if order contains a product named "USB Cable"

Requirements:
- The Order class must not contain discount logic
- No conditional discount logic inside Order
- Rules must be evaluated dynamically
- Business rules must be readable and maintainable
- Final total must never be negative

Constraints:
- An order must have at least one item
- Item price and quantity validations still apply

Goal:
Demonstrate advanced understanding of:
- Separation of concerns
- Rule-based systems
- Extensible architectures
- Clean domain modeling
- Real-world scalability considerations
*/
final class DiscountResult {
    public function __construct(
        public readonly string $ruleName,
        public readonly float $originalTotal,
        public readonly float $finalTotal
    ) {}

    public function discountAmount(): float {
        return $this->originalTotal - $this->finalTotal;
    }
}

interface DiscountRule {
    public function apply(float $baseTotal, Order $order): DiscountResult;
}
 
final class PercentageDiscount implements DiscountRule {

    public function apply(float $baseTotal, Order $order): DiscountResult {
        if ($baseTotal <= 100) {
            return new DiscountResult(
                self::class,
                $baseTotal,
                $baseTotal
            );
        }

        $final = $baseTotal * 0.9;

        return new DiscountResult(
            self::class,
            $baseTotal,
            $final
        );
    }
}

final class PremiumUserDiscount implements DiscountRule {

    private const DISCOUNT = 15;

    public function apply(float $baseTotal, Order $order): DiscountResult {
        if (!$order->isPremium() || $baseTotal <= self::DISCOUNT) {
            return new DiscountResult(
                self::class,
                $baseTotal,
                $baseTotal
            );
        }

        return new DiscountResult(
            self::class,
            $baseTotal,
            $baseTotal - self::DISCOUNT
        );
    }
}

final class BulkItemsDiscount implements DiscountRule {

    private const DISCOUNT = 20;

    public function apply(float $baseTotal, Order $order): DiscountResult {
        if ($order->itemsCount() <= 10 || $baseTotal <= self::DISCOUNT) {
            return new DiscountResult(
                self::class,
                $baseTotal,
                $baseTotal
            );
        }

        return new DiscountResult(
            self::class,
            $baseTotal,
            $baseTotal - self::DISCOUNT
        );
    }
}

class OrderItem {
    private string $name;
    private float $price;
    private int $quantity;

    public function __construct(string $name, float $price, int $quantity) {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;

        if($this->price <= 0 || $this->quantity < 1)
            throw new \Exception("Nether price or quantity can be negative or zero");
    }

    public function getTotalItemPrice(){
        return $this->price * $this->quantity;
    }
}

class Order {
    private int $userId;
    private bool $isPremiumUser;
    private array $itens;

    public function __construct(int $userId, bool $isPremiumUser = false) {
        $this->userId = $userId;
        $this->isPremiumUser = $isPremiumUser;
        $this->itens = [];
    }

    public function isPremium(): bool{
        return $this->isPremiumUser;
    }

    public function getItens(): array{
        return $this->itens;
    }

    public function itemsCount(): int{
        return count($this->itens);
    }

    public function getUserId(): int{
        return $this->userId;
    }

    public function addItem(OrderItem $item): void{
        $this->itens[] = $item;
    }

    public function subTotal(): float{
        $subTotal = 0;
        foreach($this->itens as $item){
            $subTotal += $item->getTotalItemPrice();
        }
        return $subTotal;
    }

    public function getTotal(): float{
        if(count($this->itens) < 1)
            throw new \Exception("Order must have at least one item");

        return $this->subTotal();
    }   
}

final class DiscountEngine {

    /**
     * @param DiscountRule[] $rules
     */
    public function __construct(private array $rules) {}

    public function resolve(Order $order): DiscountResult {
        $baseTotal = $order->subTotal();

        $results = array_map(
            fn (DiscountRule $rule) => $rule->apply($baseTotal, $order),
            $this->rules
        );

        return $this->pickBest($results);
    }

    private function pickBest(array $results): DiscountResult {
        usort(
            $results,
            fn (DiscountResult $a, DiscountResult $b) =>
                $a->finalTotal <=> $b->finalTotal
        );

        return $results[0];
    }
}

$rules = [
    new PercentageDiscount(),
    new PremiumUserDiscount(),
    new BulkItemsDiscount(),
];

$order = new Order(userId: 1, isPremiumUser: false);

$order->addItem(new OrderItem('Keyboard', 50, 1));
$order->addItem(new OrderItem('Mouse', 30, 2));
$order->addItem(new OrderItem('USB Cable', 10, 7));

$engine = new DiscountEngine($rules);

$result = $engine->resolve($order);

echo sprintf(
    "Rule applied: %s | Original: %.2f | Final: %.2f | Discount: %.2f",
    $result->ruleName,
    $result->originalTotal,
    $result->finalTotal,
    $result->discountAmount()
);

