<?php

class ProductDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getProductById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateStock($id, $accion) {
        $producto = $this->getProductById($id);
        if ($producto) {
            $nuevoStock = $producto['cantidad_stock'];
            if ($accion === 'restar') {
                $nuevoStock--;
            } elseif ($accion === 'sumar') {
                $nuevoStock++;
            }
            $stmt = $this->conn->prepare("UPDATE productos SET cantidad_stock = ? WHERE id_producto = ?");
            $stmt->bind_param("ii", $nuevoStock, $id);
            return $stmt->execute();
        }
        return false;
    }
}
?>
