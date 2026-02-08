<?php 

/*

Done at: 2026-02-08
Challenge 4 – Best Discount Selection (Chain of Responsibility)

Objective:
Improve the discount system so that the order applies
ONLY the best applicable discount instead of stacking all discounts.

Description:
You are given an Order that contains multiple OrderItems and a subtotal.
Multiple discount rules may be applicable to the same order,
but business rules state that only ONE discount can be applied.

The system must evaluate all available discounts and select
the one that produces the lowest final price for the order.

Discount rules:
- PercentageDiscount:
  Apply 10% discount if subtotal is greater than 100.

- PremiumUserDiscount:
  Apply a fixed discount of 15 if the user is premium.

- BulkItemsDiscount:
  Apply a fixed discount of 20 if the order contains more than 10 items.

Requirements:
- Discounts must be implemented using polymorphism.
- The Order class must not contain conditional logic
  to decide which discount is better.
- Each discount must decide whether it applies or not.
- The system must evaluate all discounts and apply only the best one.
- New discounts must be added without modifying existing code.

Constraints:
- An order must contain at least one item.
- The final total must never be negative.
- Discounts must not be cumulative.

Goal:
Demonstrate strong understanding of:
- Object-Oriented Design
- Strategy Pattern
- Chain of Responsibility or similar decision-based flow
- Separation of concerns
- Real-world business rule modeling
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
        $discount = 15;

        if($total <= $discount)
            return $total;

        if($order->getIsPremiumUser())
            return $total - $discount;

        return $total;
    }
}

class BulkItemsDiscount implements Discount{
    public function apply(float $total,Order $order){
        $discount = 20;

        if($total <= $discount)
            return $total; 

        if(count($order->getItens())>10)
            return $total - $discount;
        
        return $total;
    }
}

class PickBestDiscountValue {

    private $discounts;
    private $order;
    
    public function __construct(Order $order, array $discounts){
        $this->discounts = $discounts;
        $this->order = $order;
    }

    public function apply(float $total): float{
        $values = [];

        foreach ($this->discounts as $discount) {
            $values[] = $discount->apply($total,$this->order); 
        }

        return min($values);
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
    private array $discounts;

    public function __construct(int $userId, bool $isPremiumUser = false, $discounts) {
        $this->userId = $userId;
        $this->isPremiumUser = $isPremiumUser;
        $this->itens = [];
        $this->discounts = $discounts;
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

    public function getTotal(): float{
        if(count($this->itens) < 1)
            throw new \Exception("Order must have at least one item");

        $total = $this->getSubTotal();



        $pick = new PickBestDiscountValue($this,$this->discounts);
        $discount = $pick->apply($total);

        return $discount;
    }   
}

$discountClasses = [
    new PercentageDiscount(),
    new PremiumUserDiscount(),
    new BulkItemsDiscount(),
];

$order = new Order(userId: 1, isPremiumUser: false, discounts: $discountClasses);

$order->addItem(new OrderItem("Keyboard", 50, 1));
$order->addItem(new OrderItem("Mouse", 30, 2));
$order->addItem(new OrderItem("USB Cable", 10, 7));

$response = $order->getTotal();

echo "Best discount applied is : ".$response; 
