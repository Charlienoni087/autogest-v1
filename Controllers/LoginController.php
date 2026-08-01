<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../Config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['login_error'] = "Acceso inválido.";
    header("Location: /AutoGest/index.php");
    exit;
}

$correo = trim($_POST['correo'] ?? '');
$pass = trim($_POST['contrasena'] ?? '');

if ($correo === '' || $pass === '') {
    $_SESSION['login_error'] = "Debes completar correo y contraseña.";
    header("Location: /AutoGest/index.php");
    exit;
}



$stmt = $conexion->prepare("SELECT id_usuario, nombre_usuario, correo, contrasena, rol FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $passwordOk = password_verify($pass, $row['contrasena']);

    if (!$passwordOk && $pass === $row['contrasena']) {
        $passwordOk = true;
        $newHash = password_hash($pass, PASSWORD_DEFAULT);
        $updateStmt = $conexion->prepare("UPDATE usuarios SET contrasena = ? WHERE id_usuario = ?");
        $updateStmt->bind_param("si", $newHash, $row['id_usuario']);
        $updateStmt->execute();
    }

    if ($passwordOk) {
        session_regenerate_id(true);
        $_SESSION['id_usuario'] = (int) $row['id_usuario'];
        $_SESSION['nombre_usuario'] = htmlspecialchars($row['nombre_usuario'], ENT_QUOTES, 'UTF-8');
        $_SESSION['correo'] = htmlspecialchars($row['correo'], ENT_QUOTES, 'UTF-8');
        $_SESSION['rol'] = $row['rol'];
        $_SESSION['login_exitoso'] = true;
        unset($_SESSION['login_error']);

        header("Location: /AutoGest/Views/main.php");
        exit;
    }
}

$_SESSION['login_error'] = "Correo o contraseña incorrectos.";
header("Location: /AutoGest/index.php");
exit;