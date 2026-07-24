<?php
require_once("../Config/conexion.php");

// Verificar conexión
if (!$conexion) {
    die("No fue posible conectar con la base de datos.");
}

// Verificar que exista la tabla Usuarios
$tabla = $conexion->query("SHOW TABLES LIKE 'Usuarios'");

if ($tabla->num_rows == 0) {
    die("No existe la tabla Usuarios.");
}

// Verificar si ya existen usuarios
$sql = "SELECT COUNT(*) AS Total FROM Usuarios";
$result = $conexion->query($sql);
$fila = $result->fetch_assoc();

if ($fila['Total'] > 0) {

    if (!file_exists("terminado.lock")) {
        file_put_contents("terminado.lock", "");
    }

    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Instalación AutoGest</title>

    <link rel="stylesheet" href="../assets/css/installer.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>


    <div id="loader" class="text-center">

    <div class="spinner-border text-success" style="width:5rem;height:5rem;" role="status">
        <span class="visually-hidden">Cargando...</span>
    </div>

    <h3 class="mt-4" style="color: white;">AutoGest - Verificando...</h3>

    <p class="text-secondary">
        Comprobando configuración del sistema...
    </p>

</div>

<div id="contenido" style="display:none;">

    <div class="contenedor">

    <div class="card-installer">

    <div class="logo">

        <img src="../assets/autogest-logo.png" alt="Logo AutoGest">

    </div>

    <h2>Configuración Inicial</h2>

    <p>
        Bienvenido al asistente de instalación de <strong>AutoGest</strong>.
    </p>

    <div class="estado">

        <div><i class="fa-solid fa-circle-info"></i>Crea tus credenciales de administrador para ingresar al sistema</div>

    </div>

    <form action="InstallController.php" method="POST">

        <div class="mb-3">

        <label>Nombre del Administrador</label>

        <input
        type="text"
        name="nombre"
        class="form-control"
        required>

        </div>

        <div class="mb-3">

        <label>Correo</label>

        <input
        type="text"
        name="correo"
        class="form-control"
        required>

        </div>

        <div class="mb-4">

        <label>Contraseña</label>

        <input
        type="password"
        name="password"
        class="form-control"
        required>

        </div>

        <button class="btn btn-success w-100">

        <i class="fa-solid fa-user-shield"></i>

        Crear Administrador

        </button>

    </form>

    </div>

    </div>

</div>

    <script src="../scripts/installer.js"></script>

</body>

</html>