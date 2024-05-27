<?php
session_start();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productoId = $_POST['producto_id'];
    array_push($_SESSION['carrito'], $productoId);
}

echo json_encode($_SESSION['carrito']);
?>
