<?php

require_once("../Config/conexion.php");

$nombre = trim($_POST["nombre"]);
$correo = trim($_POST["correo"]);
$password = $_POST["password"];

$rol = "SuperAdmin";

$password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO Usuarios (nombre_usuario, correo, contrasena, rol)
        VALUES (?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "ssss",
    $nombre,
    $correo,
    $password,
    $rol
);

if ($stmt->execute()) {

    file_put_contents("terminado.lock", "");

    header("Location: ../index.php");
    exit();

} else {

    echo "Error al crear el administrador.";

}

$stmt->close();
$conexion->close();

?>