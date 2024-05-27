<?php
include 'PaymentFactory.php';

// Ejemplo de uso
$amount = 100.00; // Monto de ejemplo

// Crear una instancia de la fábrica correspondiente (puede ser CreditCardPaymentFactory o PaypalPaymentFactory)
$paymentFactory = new CreditCardPaymentFactory();
$paymentMethod = $paymentFactory->createPayment();
$paymentMethod->pay($amount);
?>
