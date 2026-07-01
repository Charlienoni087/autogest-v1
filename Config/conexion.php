<?php
$host = "localhost";
$db = "autogest";
$user = "root";
$pass = "";
try {
    $conexion = new mysqli($host, $user, $pass, $db);
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }
    $conexion->set_charset("utf8mb4");
} catch (Exception $e) {
    die($e->getMessage());
}
?>