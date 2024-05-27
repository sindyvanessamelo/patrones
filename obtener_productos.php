<?php
require_once 'conexion.php';

if (!isset($_GET['id'])) {
    die('ID de producto no especificado.');
}

$id_producto = intval($_GET['id']);

$conn = Database::getInstance()->getConnection();
$stmt = $conn->prepare("SELECT id_producto, nombre, descripcion, precio, cantidad_stock, categoria, imagen FROM productos WHERE id_producto = ?");
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $producto = $result->fetch_assoc();
    echo json_encode($producto);
} else {
    echo json_encode(['error' => 'Producto no encontrado.']);
}

$stmt->close();
$conn->close();
?>
