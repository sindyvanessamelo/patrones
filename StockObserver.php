<?php
class StockObserver {
    public function update($productId, $newStock) {
        // Enviar notificación o realizar alguna acción
        if ($newStock < 5) {
            echo "El producto con ID $productId tiene un stock bajo ($newStock unidades).";
        }
    }
}
?>
