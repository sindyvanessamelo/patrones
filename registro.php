<?php
require_once 'conexion.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y sanear los datos recibidos
    $nombre_usuario = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Verificar que todos los campos están llenos
    if ($nombre_usuario && $email && $password) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Obtener la conexión a la base de datos
            $connection = Database::getInstance()->getConnection();
            $stmt = $connection->prepare("INSERT INTO usuarios (nombre_usuario, email, password) VALUES (?, ?, ?)");

            if (!$stmt) {
                throw new Exception('Error al preparar la consulta: ' . $connection->error);
            }

            $stmt->bind_param("sss", $nombre_usuario, $email, $passwordHash);

            // Ejecutar la consulta y verificar el resultado
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Usuario registrado con éxito';
            } else {
                throw new Exception('Error al registrar el usuario: ' . $stmt->error);
            }

            // Cerrar la declaración
            $stmt->close();
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
    } else {
        $response['message'] = 'Todos los campos son obligatorios';
    }
}

echo json_encode($response);
?>
