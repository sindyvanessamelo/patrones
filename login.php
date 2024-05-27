<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = Database::getInstance()->getConnection();
    $stmt = $conn->prepare("SELECT id, password FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        header('Location: index.html');
        exit();
    } else {
        echo 'Credenciales incorrectas';
    }

    $stmt->close();
    $conn->close();
}
?>
