<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id'])) {
    die('Usuario no autenticado');
}

$user_id = $_SESSION['user_id'];
$carrito = isset($_POST['carrito']) ? json_decode($_POST['carrito'], true) : [];

if (empty($carrito)) {
    error_log('Carrito vacío');
    die('Carrito vacío');
}

$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$tarjeta = $_POST['tarjeta'];

error_log('Datos recibidos: ' . json_encode($_POST));

$conn = Database::getInstance()->getConnection();
$conn->begin_transaction();

try {
    // Insertar compra
    $stmt = $conn->prepare("INSERT INTO compras (user_id, nombre, direccion, tarjeta, fecha_compra) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isss", $user_id, $nombre, $direccion, $tarjeta);
    $stmt->execute();
    $compra_id = $stmt->insert_id;
    $stmt->close();

    // Insertar detalles de la compra y actualizar stock
    $stmt = $conn->prepare("INSERT INTO detalles_compra (compra_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
    $updateStockStmt = $conn->prepare("UPDATE productos SET cantidad_stock = cantidad_stock - ? WHERE id_producto = ?");
    foreach ($carrito as $producto) {
        $producto_id = $producto['id'];
        $cantidad = $producto['cantidad'];
        $precio = $producto['precio'];
        $stmt->bind_param("iiid", $compra_id, $producto_id, $cantidad, $precio);
        $stmt->execute();

        // Actualizar stock
        $updateStockStmt->bind_param("ii", $cantidad, $producto_id);
        $updateStockStmt->execute();
    }
    $stmt->close();
    $updateStockStmt->close();

    $conn->commit();
    echo 'Pago realizado con éxito';
} catch (mysqli_sql_exception $exception) {
    $conn->rollback();
    throw $exception;
}

$conn->close();
?>
