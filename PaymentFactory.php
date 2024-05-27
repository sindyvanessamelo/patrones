<?php
abstract class PaymentFactory {
    abstract public function createPayment();
}

class CreditCardPaymentFactory extends PaymentFactory {
    public function createPayment() {
        return new CreditCardPayment();
    }
}

class PaypalPaymentFactory extends PaymentFactory {
    public function createPayment() {
        return new PaypalPayment();
    }
}

interface PaymentMethod {
    public function pay($amount);
}

class CreditCardPayment implements PaymentMethod {
    public function pay($amount) {
        echo "Pago de $amount realizado con tarjeta de crédito.";
    }
}

class PaypalPayment implements PaymentMethod {
    public function pay($amount) {
        echo "Pago de $amount realizado con Paypal.";
    }
}
?>
