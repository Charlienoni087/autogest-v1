<?php
session_start();
require_once __DIR__ . '/conexion.php';

if (!isset($conexion)) {
    die('Error de conexión a la base de datos.');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['nombre_usuario'];
    $pass = $_POST['contrasena'];

    $stmt = $conexion->prepare("SELECT id_usuario, contrasena FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row['contrasena'])) {
            $_SESSION['id_usuario'] = $row['id_usuario'];
            header("Location: dashboard.php");
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}
?>