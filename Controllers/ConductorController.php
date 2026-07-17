<?php

require_once __DIR__.'/../config/conexion.php';
require_once __DIR__.'/../Models/conductor.php';
require_once __DIR__.'/../Models/licencia.php';

$modeloConductor = new Conductor($conexion);
$modeloLicencia = new Licencia($conexion);

/*Procedimientos para conductores*/

// Agregar un conductor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_conductor'])) {
    $nombre      = $_POST['nombre_conductor'];
    $cedula      = $_POST['numero_cedula'];
    $telefono    = $_POST['telefono'];
    $tipo_sangre = $_POST['tipo_sangre'];
    
    $estado      = intval($_POST['estado_conductor']); 
    
    $id_licencia = intval($_POST['id_licencia']); 

    if ($modeloConductor->crear($nombre, $cedula, $telefono, $tipo_sangre, $id_licencia, $estado)) {
        header("Location: main.php?page=conductores&save_success=conductor");
        exit();
    } else {
        echo "<div class='alert alert-danger m-2'>Error al guardar el conductor en la base de datos.</div>";
    }
}

// Editar un conductor
if($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_POST['editar_conductor'])) {
    $nombre = $_POST['nombre_conductor'];
    $cedula = $_POST['numero_cedula'];
    $telefono = $_POST['telefono'];
    $tipo_sangre = $_POST['tipo_sangre'];

    $estado = intval($_POST['estado_conductor']);
    $id_licencia = intval($_POST['id_licencia']);
    $id = intval($_POST['id_conductor']);

    if($modeloConductor->actualizar($id, $nombre, $cedula, $telefono, $tipo_sangre, $id_licencia, $estado)) {
        header('Location: main.php?page=conductores&update_success=conductor');
        exit();
    } else {
        echo "<div class='alert alert-danger m-2'>Error al guardar el conductor en la base de datos.</div>";
    }

}


// Bloque de codigo para cargar datos en edicion en conductores
$en_modo_edicion = false;
$u_id = ""; $u_nombre = ""; $u_cedula = ""; $u_telefono = ""; $u_tipo_sangre = ""; $u_estado = ""; $u_id_licencia = "";

if(isset($_GET['editar_conductor'])){
    $en_modo_edicion = true;

    $id_editar = intval($_GET['editar_conductor']);

    $driver_data = $modeloConductor->obtenerPorId($id_editar);

    if ($driver_data) {
        $u_id           = $driver_data['id_conductor'];
        $u_nombre       = $driver_data['nombre_conductor'];
        $u_cedula       = $driver_data['cedula'];
        $u_telefono     = $driver_data['telefono'];
        $u_tipo_sangre  = $driver_data['tipo_sangre'];
        $u_estado       = $driver_data['estado'];
        $u_id_licencia  = $driver_data['id_licencia'];
    }
    
} 

if (isset($_GET['eliminar_conductor'])) {
    $id = intval($_GET['eliminar_conductor']);
    if ($modeloConductor->eliminar($id)) {
        header("Location: main.php?page=conductores&delete_success=conductor");
        exit();
    } else {
        echo "<div class='alert alert-danger m-2'>Error al eliminar el conductor.</div>";
    }
}

$listaConductores = $modeloConductor->obtenerTodos();
$listaLicencias = $modeloLicencia->obtenerTodas();

// Metodo para crear licencias
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_licencia'])) {
    $numero_licencia = $_POST['numero_licencia'];
    $tipo_licencia = $_POST['tipo_licencia'];
    $categorias = implode(", ", $_POST['categorias'] ?? []);
    $fecha_vencimiento = $_POST['fecha_vencimiento'];

    if ($modeloLicencia->crear($numero_licencia, $tipo_licencia, $categorias, $fecha_vencimiento)) {
        header("Location: main.php?page=conductores&save_success=licencia");
        exit();
    } else {
        echo "<div class='alert alert-danger m-2'>Error al guardar la licencia en la base de datos.</div>";
    }
}

//Editar licencia
if($_SERVER["REQUEST_METHOD"] === "POST"&& isset($_POST['editar_licencia'])){
    $numero_licencia = $_POST['numero_licencia'];
    $tipo_licencia = $_POST['tipo_licencia'];
    $categorias = implode(", ", $_POST['categorias'] ?? []);
    $fecha_vencimiento = $_POST['fecha_vencimiento'];

    $id = intval($_POST['id_licencia']);

    if($modeloLicencia->actualizar($id, $numero_licencia, $tipo_licencia, $categorias, $fecha_vencimiento)) {
        header('Location: main.php?page=conductores&update_success=licencia');
        exit();
    } else {
        echo "<div class='alert alert-danger m-2'>Error al guardar la licencia en la base de datos.</div>";
    }
}

if (isset($_GET['eliminar_licencia'])) {
    $id = intval($_GET['eliminar_licencia']);
    if ($modeloLicencia->eliminar($id)) {
        header("Location: main.php?page=conductores&delete_success=licencia");
        exit();
    } else {
        echo "<div class='alert alert-danger m-2'>Error al eliminar el conductor.</div>";
    }
}

// Bloque de codigo para cargar datos en edicion de licencias
$en_modo_edicion_lic = false;
$u_id_lic = ""; $u_numero = ""; $u_tipo = ""; $u_categorias = ""; $u_fecha_vencimiento = "";

if(isset($_GET['editar_licencia'])){
    $en_modo_edicion_lic = true;

    $id_editar = intval($_GET['editar_licencia']);

    $licencia_data = $modeloLicencia->obtenerPorId($id_editar);

    if ($licencia_data) {
        $u_id_lic             = $licencia_data['id_licencia'];
        $u_numero             = $licencia_data['numero_licencia'];
        $u_tipo               = $licencia_data['tipo_licencia'];
        $u_categorias         = $licencia_data['categorias'];
        $u_fecha_vencimiento  = $licencia_data['fecha_vencimiento'];
    }
    
} 

// En este bloque de codigo se muestra un mensaje de éxito si se ha guardado un conductor o una licencia correctamente
if (isset($_GET['save_success']) || isset($_GET['update_success']) || isset($_GET['delete_success'])) {
    if (isset($_GET['save_success'])) {
        $tipo = $_GET['save_success'];
        $mensaje = ($tipo === 'conductor') ? '¡Conductor guardado con éxito!' : '¡Licencia guardada con éxito!';
    } elseif (isset($_GET['update_success'])) {
        $tipo = $_GET['update_success'];
        $mensaje = ($tipo === 'conductor') ? '¡Conductor actualizado con éxito!' : '¡Licencia actualizada con éxito!';
    } else {
        $tipo = $_GET['delete_success'];
        $mensaje = ($tipo === 'conductor') ? '¡Conductor eliminado con éxito!' : '¡Licencia eliminada con éxito!';
    }

    echo '<div id="alertaExito" class="alert alert-success alert-dismissible fade show m-2" role="alert">
            ' . $mensaje . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
          </div>';
}

require_once __DIR__ .'/../Views/modConductor.php';

?>
