<?php 

/**
 * Done at: 2026-02-06
 * 
 * Code Challenge #2 — Order Processing with Discounts (OOP)
 *
 * Description:
 * Build a simple order processing system using Object-Oriented PHP.
 * Each order belongs to a user and contains multiple items.
 * The final order total must be calculated based on business rules.
 *
 * Domain Objects:
 *
 * OrderItem:
 * - Represents a single item in an order.
 * - Properties:
 *     - name (string)
 *     - price (float)
 *     - quantity (int)
 * - Rules:
 *     - price must be greater than zero
 *     - quantity must be at least 1
 *
 * Order:
 * - Represents a user's order.
 * - Properties:
 *     - userId (int)
 *     - isPremiumUser (bool)
 *     - collection of OrderItem objects
 * - Rules:
 *     - An order must contain at least one item
 *     - Subtotal is the sum of (price × quantity) for all items
 *
 * Discounts:
 * - Apply discounts in the following order:
 *     1. Apply a 10% discount if the subtotal is greater than 100
 *     2. Apply an additional fixed discount of 15 if the user is a premium user
 *
 * Requirements:
 * - Use proper encapsulation (no public properties).
 * - Keep business rules inside domain objects.
 * - Avoid shared mutable state for calculated values.
 * - Code should be readable, maintainable, and interview-ready.
 *
 * Expected Result:
 * - The system should correctly calculate the final order total
 *   based on the defined rules and item list.
 *
 * Notes:
 * - No frameworks or external libraries.
 * - No database or persistence layer.
 * - Focus on design, correctness, and clarity.
 */

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

    public function getUserId(): int{
        return $this->userId;
    }

    public function addItem(OrderItem $item): void{
        $this->itens[] = $item;
    }

    public function getSubTotal(): float{
        $subTotal = 0;
        foreach($this->itens as $item){
            $subTotal += $item->getTotalItemPrice();
        }
        return $subTotal;
    }

    public function getTotal(): float{

        if(count($this->itens) < 1)
            throw new \Exception("Order must have at least one item");

        $total = $this->getSubTotal();

        if($total > 100)
            $total = ($total) - ($total * 0.1);

        if($this->isPremiumUser)
            $total = $total - 15;

        return $total;
    }
}

$order = new Order(userId: 1, isPremiumUser: true);

$order->addItem(new OrderItem("Keyboard", 50, 1));
$order->addItem(new OrderItem("Mouse", 30, 2));

echo "Order from user_id ".$order->getUserId() .", Final value is : " .$order->getTotal(). PHP_EOL;
