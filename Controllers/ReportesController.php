<?php

require_once __DIR__ . '/../Models/reportes.php';

if (!isset($conexion)) {
    require_once __DIR__ . '/../Config/conexion.php';
}

$reportesModel = new Reportes($conexion);

$mensaje = "";
$tipo_mensaje = ""; 

// CREAR
if (isset($_POST['guardar_reporte'])) {
    $fecha = $_POST['fecha'];
    $hora_entrada = $_POST['hora_entrada'];
    $hora_salida = $_POST['hora_salida'];
    $id_conductor = intval($_POST['id_conductor']);
    $id_vehiculo = intval($_POST['id_vehiculo']);

    if ($reportesModel->crear($fecha, $hora_entrada, $hora_salida, $id_conductor, $id_vehiculo)) {
        $mensaje = "¡Reporte registrado con éxito!";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al registrar el reporte: " . $conexion->error;
        $tipo_mensaje = "danger";
    }
}

// ACTUALIZAR
if (isset($_POST['actualizar_reporte'])) {
    $id_reporte = intval($_POST['id_reporte']);
    $fecha = $_POST['fecha'];
    $hora_entrada = $_POST['hora_entrada'];
    $hora_salida = $_POST['hora_salida'];
    $id_conductor = intval($_POST['id_conductor']);
    $id_vehiculo = intval($_POST['id_vehiculo']);

    if ($reportesModel->actualizar($id_reporte, $fecha, $hora_entrada, $hora_salida, $id_conductor, $id_vehiculo)) {
        $mensaje = "¡Reporte actualizado correctamente!";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al actualizar el reporte: " . $conexion->error;
        $tipo_mensaje = "danger";
    }
}

// ELIMINAR
if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);
    if ($reportesModel->eliminar($id_eliminar)) {
        $mensaje = "¡Reporte eliminado correctamente!";
        $tipo_mensaje = "warning";
    } else {
        $mensaje = "Error al eliminar el reporte: " . $conexion->error;
        $tipo_mensaje = "danger";
    }
}

// DATOS PARA EDICION
$en_modo_edicion = false;
$r_id = "";
$r_fecha = "";
$r_hora_entrada = "";
$r_hora_salida = "";
$r_id_conductor = "";
$r_id_vehiculo = "";

if (isset($_GET['editar'])) {
    $id_editar = intval($_GET['editar']);
    $datos = $reportesModel->obtenerPorId($id_editar);
    if ($datos) {
        $en_modo_edicion = true;
        $r_id = $datos['id_reporte'];
        $r_fecha = $datos['fecha'];
        $r_hora_entrada = $datos['hora_entrada'];
        $r_hora_salida = $datos['hora_salida'];
        $r_id_conductor = $datos['id_conductor'];
        $r_id_vehiculo = $datos['id_vehiculo'];
    }
}

// FILTROS DE BÚSQUEDA
$f_fecha_inicio = $_GET['fecha_inicio'] ?? '';
$f_fecha_fin = $_GET['fecha_fin'] ?? '';
$f_id_conductor = $_GET['f_id_conductor'] ?? '';
$f_id_vehiculo = $_GET['f_id_vehiculo'] ?? '';

$hay_filtros_activos = !empty($f_fecha_inicio) || !empty($f_fecha_fin) || !empty($f_id_conductor) || !empty($f_id_vehiculo);

if ($hay_filtros_activos) {
    $listaReportes = $reportesModel->obtenerFiltrados(
        $f_fecha_inicio ?: null,
        $f_fecha_fin ?: null,
        $f_id_conductor !== '' ? intval($f_id_conductor) : null,
        $f_id_vehiculo !== '' ? intval($f_id_vehiculo) : null
    );
} else {
    $listaReportes = $reportesModel->obtenerTodos();
}

$listaConductores = $reportesModel->listarConductores();
$listaVehiculos = $reportesModel->listarVehiculos();
?>
