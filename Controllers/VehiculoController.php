<?php

require_once __DIR__.'/../config/conexion.php';
require_once __DIR__.'/../Models/vehiculo.php';

// En tu controlador

$modeloVehiculo = new Vehiculo($conexion);


//marca, modelo, color, chasis, tipo_vehiculo, tipo_combustible, estado, numero_poliza, gravamen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_vehiculo'])) {
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $color = $_POST['color'];
    $chasis = $_POST['chasis'];
    $tipo_vehiculo = $_POST['tipo_vehiculo'];
    $tipo_combustible = $_POST['combustible'];
    $estado = $_POST['estado_vehiculo'];
    $numero_poliza = $_POST['numero_poliza'];
    $gravamen = intval($_POST['gravamen']);


    if ($modeloVehiculo->crearvehiculo($marca, $modelo, $color, $chasis, $tipo_vehiculo, $tipo_combustible, $estado, $numero_poliza, $gravamen)) {
        header("Location: main.php?page=vehiculos&save_success=vehiculo");
        exit();
    } else {
        echo "<div class='alert alert-danger m-2'>Error al guardar el vehículo en la base de datos.</div>";
    }
}


if($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_POST['editar_vehiculo'])) {
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $color = $_POST['color'];
    $chasis = $_POST['chasis'];
    $tipo_vehiculo = $_POST['tipo_vehiculo'];
    $tipo_combustible = $_POST['combustible'];
    $estado = $_POST['estado_vehiculo'];
    $numero_poliza = $_POST['numero_poliza'];
    $gravamen = intval($_POST['gravamen']);

    $id = intval($_POST['id_vehiculo']);

    if($modeloVehiculo->actualizarVehiculo($id, $marca, $modelo, $color, $chasis, $tipo_vehiculo, $tipo_combustible, $estado, $numero_poliza, $gravamen)) {
        header('Location: main.php?page=vehiculos&update_success=vehiculo');
        exit();
    } else {
        echo "<div class='alert alert-danger m-2'>Error al guardar el vehículo en la base de datos.</div>";
    }

}


    
// Bloque de codigo para cargar datos en edicion en conductores
$en_modo_edicion = false;
$u_id = ""; $u_marca = ""; $u_modelo = ""; $u_color = ""; $u_chasis = ""; $u_tipo_vehiculo = ""; $u_tipo_combustible = ""; $u_estado = ""; $u_numero_poliza = ""; $u_gravamen = "";

if(isset($_GET['editar_vehiculo'])){
    $en_modo_edicion = true;

    $id_editar = intval($_GET['editar_vehiculo']);

    $vehicle_data = $modeloVehiculo->obtenerVehiculoPorId($id_editar);

    if ($vehicle_data) {
        $u_id           = $vehicle_data['id_vehiculo'];
        $u_marca        = $vehicle_data['marca'];
        $u_modelo       = $vehicle_data['modelo'];
        $u_color        = $vehicle_data['color'];
        $u_chasis       = $vehicle_data['chasis'];
        $u_tipo_vehiculo = $vehicle_data['tipo_vehiculo'];
        $u_tipo_combustible = $vehicle_data['combustible'];
        $u_estado       = $vehicle_data['estado_vehiculo'];
        $u_numero_poliza = $vehicle_data['numero_poliza'];
        $u_gravamen     = $vehicle_data['gravamen'];
    }
    
} 

$listaVehiculos = $modeloVehiculo->obtenerVehiculos();


if (isset($_GET['eliminar_vehiculo'])) {
    $id = intval($_GET['eliminar_vehiculo']);
    if ($modeloVehiculo->eliminarVehiculo($id)) {
        header("Location: main.php?page=vehiculos&delete_success=vehiculo");
        exit();
    } else {
        echo "<div class='alert alert-danger m-2'>Error al eliminar el vehículo.</div>";
    }
}


// En este bloque de codigo se muestra un mensaje de éxito si se ha guardado un vehículo correctamente
if (isset($_GET['save_success']) || isset($_GET['update_success']) || isset($_GET['delete_success'])) {
    if (isset($_GET['save_success'])) {
        $tipo = $_GET['save_success'];
        $mensaje = ($tipo === 'vehiculo') ? '¡Vehículo guardado con éxito!' : '';
    } elseif (isset($_GET['update_success'])) {
        $tipo = $_GET['update_success'];
        $mensaje = ($tipo === 'vehiculo') ? '¡Vehículo actualizado con éxito!' : '';
    } else {
        $tipo = $_GET['delete_success'];
        $mensaje = ($tipo === 'vehiculo') ? '¡Vehículo eliminado con éxito!' : '';
    }

    echo '<div id="alertaExito" class="alert alert-success alert-dismissible fade show m-2" role="alert">
            ' . $mensaje . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
          </div>';
}



require_once __DIR__ .'/../Views/modVehiculo.php';

?>