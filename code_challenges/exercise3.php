<?php 

/*

Done at: 2026-02-07
Challenge 3 – Discount System with Polymorphism (Strategy Pattern)

Objective:
Refactor the order discount logic to make it extensible, maintainable,
and compliant with SOLID principles, especially the Open/Closed Principle.

Description:
You are given an Order that contains multiple OrderItems.
Each Order has a subtotal calculated from its items.

The system must support multiple discount rules that can be applied
to an order without modifying the Order class itself.

Implement a Discount interface and create different discount strategies:

- PercentageDiscount:
  Apply a 10% discount only if the order subtotal is greater than 100.

- PremiumUserDiscount:
  Apply a fixed discount of 15 if the order belongs to a premium user.

- BulkItemsDiscount:
  Apply a fixed discount of 20 if the order contains more than 10 items.

Requirements:
- The Order class must not contain conditional logic related to discounts.
- Discounts must be applied using polymorphism.
- New discount types should be added without changing existing code.
- The order total should be calculated by applying a list of discounts
  sequentially.

Constraints:
- An order must contain at least one item.
- Item price must be greater than zero.
- Item quantity must be at least one.

Goal:
Demonstrate proper use of Object-Oriented Programming,
interfaces, polymorphism, and clean architecture principles
in a real-world business scenario.
*/

interface Discount{
    public function apply(float $actualTotal,Order $order);
}

class PercentageDiscount implements Discount{
    public function apply(float $total,Order $order){
        if($total > 100)
            return ($total) - ($total * 0.1);
        return $total;
    }
}

class PremiumUserDiscount implements Discount{
    public function apply(float $total,Order $order){
        if($order->getIsPremiumUser())
            return $total - 15;
        return $total;
    }
}

class BulkItemsDiscount implements Discount{
    public function apply(float $total,Order $order){
        if(count($order->getItens())>10)
            return $total - 20;
        return $total;
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

    public function getIsPremiumUser(): bool{
        return $this->isPremiumUser;
    }

    public function getItens(): array{
        return $this->itens;
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

    public function getTotal(array $discounts): float{
        if(count($this->itens) < 1)
            throw new \Exception("Order must have at least one item");

        $total = $this->getSubTotal();

        foreach ($discounts as $discount) {
            $total = $discount->apply($total,$this);
        }

        return $total;
    }   
}

$order = new Order(userId: 1, isPremiumUser: true);

$order->addItem(new OrderItem("Keyboard", 50, 1));
$order->addItem(new OrderItem("Mouse", 30, 2));
$order->addItem(new OrderItem("USB Cable", 10, 7));

$discounts = [
    new PercentageDiscount(),
    new PremiumUserDiscount(),
    new BulkItemsDiscount(),
];

$response = $order->getTotal($discounts);

echo "Discounts applied: ".$response; 

// echo "Discounts applied: ".PHP_EOL; 
// echo "10% : ".$response[0].PHP_EOL; 
// echo "Premium discount: ".$response[1].PHP_EOL; 
// echo "Bulk items discount: ".$response[2].PHP_EOL;