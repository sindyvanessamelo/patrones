<?php
class Product {
    private $name;
    private $stock;

    public function __construct($name, $stock) {
        $this->name = $name;
        $this->stock = $stock;
    }

    public function getName() {
        return $this->name;
    }

    public function getStock() {
        return $this->stock;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }
}
?>
