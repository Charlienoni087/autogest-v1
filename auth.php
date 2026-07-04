<?php
// 1. Iniciamos sesión y traemos la conexión
session_start();
require_once 'config/conexion.php';
if (!isset($conn)) {
    if (isset($conexion)) {
        $conn = $conexion;
    } elseif (isset($mysqli)) {
        $conn = $mysqli;
    } elseif (isset($link)) {
        $conn = $link;
    } else {
        die('Error: database connection not available.');
    }
}

// 2. Verificamos si llegaron datos desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_ingresado = $_POST['nombre_usuario'];
    $pass_ingresada = $_POST['contrasena'];

    // 3. Consulta segura (Prevenimos SQL Injection)
    $stmt = $conn->prepare("SELECT id_usuario, contrasena FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $usuario_ingresado);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // 4. Verificamos si existe el usuario
    if ($fila = $resultado->fetch_assoc()) {
        // 5. Validamos la contraseña encriptada
        if (password_verify($pass_ingresada, $fila['contrasena'])) {
            $_SESSION['id_usuario'] = $fila['id_usuario'];
            header("Location: dashboard.php"); // ¡Conexión exitosa!
            exit();
        }
    }
    
    // Si algo falló, regresamos al index
    header("Location: index.php?error=1");
}
?>