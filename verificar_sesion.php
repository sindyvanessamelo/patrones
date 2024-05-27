<?php
session_start();

$response = array("autenticado" => false);

if (isset($_SESSION['user_id'])) {
    $response["autenticado"] = true;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
