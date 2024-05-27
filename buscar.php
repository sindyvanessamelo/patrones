<?php
require 'conexion.php';

$query = $_GET['query'] ?? '';
$categoria = $_GET['categoria'] ?? 'todas';

$conn = Database::getInstance()->getConnection();

$sql = "SELECT * FROM productos WHERE nombre LIKE ?";

$params = ["%$query%"];

if ($categoria !== 'todas') {
    $sql .= " AND categoria = ?";
    $params[] = $categoria;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <h1>Resultados de Búsqueda</h1>
        
        <a href="index.html">Volver a la tienda</a>
    </header>

    <main>
        <?php if (count($productos) > 0): ?>
            <div class="product-grid">
                <?php foreach ($productos as $producto): ?>
                    <div class="product-item">
                        <div class="imagen-container">
                            <img src="images/<?= $producto['imagen'] ?>" alt="<?= $producto['nombre'] ?>">
                        </div>
                        <h3><?= $producto['nombre'] ?></h3>
                        <p><?= $producto['descripcion'] ?></p>
                        <p>Precio: $<?= $producto['precio'] ?></p>
                        <button onclick="agregarAlCarrito(<?= $producto['id_producto'] ?>)">Agregar al carrito</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No se encontraron productos.</p>
        <?php endif; ?>
    </main>

    <script src="js/main.js"></script>
</body>
</html>
