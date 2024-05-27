
<?php
interface PaymentStrategy {
    public function pay($amount);
}

class CreditCardStrategy implements PaymentStrategy {
    public function pay($amount) {
        echo "Paid $amount using Credit Card<br>";
    }
}

class PayPalStrategy implements PaymentStrategy {
    public function pay($amount) {
        echo "Paid $amount using PayPal<br>";
    }
}

class ShoppingCart {
    private $amount;
    private $paymentStrategy;

    public function __construct($amount) {
        $this->amount = $amount;
    }

    public function setPaymentStrategy(PaymentStrategy $paymentStrategy) {
        $this->paymentStrategy = $paymentStrategy;
    }

    public function checkout() {
        $this->paymentStrategy->pay($this->amount);
    }
}
?>
