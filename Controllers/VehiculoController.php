<?php

require_once __DIR__.'/../config/conexion.php';
require_once __DIR__.'/../Models/vehiculo.php';
require_once __DIR__.'/../Models/conductor.php';
require_once __DIR__.'/../Models/circulacion.php';

// En tu controlador

$modeloVehiculo = new Vehiculo($conexion);
$modeloCirculacion = new Circulacion($conexion);


//marca, modelo, color, chasis, tipo_vehiculo, tipo_combustible, estado, numero_poliza, gravamen
$en_modo_edicion = false;
$marca = ""; $modelo = ""; $color = ""; $chasis = ""; $tipo_vehiculo = ""; $tipo_combustible = ""; $estado = ""; $numero_poliza = ""; $gravamen = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['agregar_vehiculo']) || isset($_POST['editar_vehiculo']))) {
    $marca = trim($_POST['marca'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $chasis = trim($_POST['chasis'] ?? '');
    $tipo_vehiculo = trim($_POST['tipo_vehiculo'] ?? '');
    $tipo_combustible = trim($_POST['combustible'] ?? '');
    $estado = $_POST['estado_vehiculo'] ?? '';
    $numero_poliza = trim($_POST['numero_poliza'] ?? '');
    $gravamen = intval($_POST['gravamen'] ?? 0);
    $id_conductor = intval($_POST['id_conductor'] ?? 0);
    $id_circulacion = intval($_POST['id_circulacion'] ?? 0);

    // Validaciones
    if (empty($marca) || empty($modelo) || empty($chasis)) {
        $error = "Marca, modelo y chasis son obligatorios.";
    } elseif ($id_conductor <= 0) {
        $error = "Debes seleccionar un conductor.";
    } elseif ($id_circulacion <= 0) {
        $error = "Debes seleccionar una circulación (placa).";
    } else {
        // Ejecutar acción según el botón presionado
        if (isset($_POST['agregar_vehiculo'])) {
            if ($modeloVehiculo->crearVehiculo($marca, $modelo, $color, $chasis, $tipo_vehiculo, $tipo_combustible, $estado, $numero_poliza, $gravamen, $id_conductor, $id_circulacion)) {
                header("Location: main.php?page=vehiculos&save_success=vehiculo");
                exit();
            } else {
                $error = "Error al guardar el vehículo en la base de datos.";
            }
        } elseif (isset($_POST['editar_vehiculo'])) {
            $id = intval($_POST['id_vehiculo']);
            if ($modeloVehiculo->actualizarVehiculo($id, $marca, $modelo, $color, $chasis, $tipo_vehiculo, $tipo_combustible, $estado, $numero_poliza, $gravamen, $id_conductor, $id_circulacion)) {
                header('Location: main.php?page=vehiculos&update_success=vehiculo');
                exit();
            } else {
                $error = "Error al actualizar el vehículo en la base de datos.";
            }
        }
    }
}


if (isset($_GET['eliminar_vehiculo'])) {
    $id = intval($_GET['eliminar_vehiculo']);
    if ($modeloVehiculo->eliminarVehiculo($id)) {
        header("Location: main.php?page=vehiculos&delete_success=vehiculo");
        exit();
    } else {
        echo "<div class='alert alert-danger m-2'>Error al eliminar el vehículo.</div>";
    }
}

// 4. CARGAR DATOS PARA MODO EDICIÓN
$en_modo_edicion = false;
$u_id = ""; $u_marca = ""; $u_modelo = ""; $u_color = ""; $u_chasis = ""; $u_tipo_vehiculo = ""; $u_tipo_combustible = ""; $u_estado = ""; $u_numero_poliza = ""; $u_gravamen = ""; $u_id_conductor = ""; $u_nombre_conductor = ""; $u_id_circulacion = ""; $u_placa = "";

if (isset($_GET['editar_vehiculo'])) {
    $id_editar = intval($_GET['editar_vehiculo']);
    $vehicle_data = $modeloVehiculo->obtenerVehiculoPorId($id_editar);

    if ($vehicle_data) {
        $en_modo_edicion    = true;
        $u_id               = $vehicle_data['id_vehiculo'];
        $u_marca            = $vehicle_data['marca'];
        $u_modelo           = $vehicle_data['modelo'];
        $u_color            = $vehicle_data['color'];
        $u_chasis           = $vehicle_data['chasis'];
        $u_tipo_vehiculo    = $vehicle_data['tipo_vehiculo'];
        $u_tipo_combustible = $vehicle_data['tipo_combustible'];
        $u_estado           = $vehicle_data['estado'];
        $u_numero_poliza    = $vehicle_data['numero_poliza'];
        $u_gravamen         = $vehicle_data['gravamen'];
        $u_id_conductor     = $vehicle_data['id_conductor'];
        $u_nombre_conductor = $vehicle_data['nombre_conductor'] ?? '';
        $u_id_circulacion   = $vehicle_data['id_circulacion'];
        $u_placa            = $vehicle_data['placa'] ?? '';
    }
}

// 5. OBTENER LISTA DE VEHÍCULOS (Para la tabla)
$listaVehiculos = $modeloVehiculo->obtenerVehiculos();

// 6. MOSTRAR MENSAJES DE ÉXITO O ERROR
if (isset($_GET['accion'])) {
    $mensajes = [
        'creado'    => '¡Vehículo guardado con éxito!',
        'editado'   => '¡Vehículo actualizado con éxito!',
        'eliminado' => '¡Vehículo eliminado con éxito!'
    ];

    $accion = $_GET['accion'];
    if (isset($mensajes[$accion])) {
        echo '<div id="alertaExito" class="alert alert-success alert-dismissible fade show m-2" role="alert">
                ' . $mensajes[$accion] . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
              </div>';
    }
}

if (isset($error)) {
    echo "<div class='alert alert-danger alert-dismissible fade show m-2'>
            {$error}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Cerrar'></button>
          </div>";
}


$listaVehiculos = $modeloVehiculo->obtenerVehiculos();


// =========================================================
// ================  CIRCULACION (nuevo)  ===================
// =========================================================

// codigo_circulacion, cilindraje, tonelaje, pasajeros, placa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_circulacion'])) {
    $codigo_circulacion = $_POST['codigo_circulacion'];
    $cilindraje = $_POST['cilindraje'];
    $tonelaje = $_POST['tonelaje'];
    $pasajeros = intval($_POST['pasajeros']);
    $placa = $_POST['placa'];

    if ($modeloCirculacion->crearCirculacion($codigo_circulacion, $cilindraje, $tonelaje, $pasajeros, $placa)) {
        header("Location: main.php?page=vehiculos&save_success=circulacion");
        exit();
    } else {
        echo "<div class='alert alert-danger m-2'>Error al guardar la circulación en la base de datos.</div>";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_circulacion'])) {
    $codigo_circulacion = $_POST['codigo_circulacion'];
    $cilindraje = $_POST['cilindraje'];
    $tonelaje = $_POST['tonelaje'];
    $pasajeros = intval($_POST['pasajeros']);
    $placa = $_POST['placa'];

    $id = intval($_POST['id_circulacion']);

    if ($modeloCirculacion->actualizarCirculacion($id, $codigo_circulacion, $cilindraje, $tonelaje, $pasajeros, $placa)) {
        header('Location: main.php?page=vehiculos&update_success=circulacion');
        exit();
    } else {
        echo "<div class='alert alert-danger m-2'>Error al actualizar la circulación en la base de datos.</div>";
    }
}


// Bloque de codigo para cargar datos en edicion de circulacion
$en_modo_edicion_circulacion = false;
$c_id = ""; $c_codigo_circulacion = ""; $c_cilindraje = ""; $c_tonelaje = ""; $c_pasajeros = ""; $c_placa = "";

if (isset($_GET['editar_circulacion'])) {
    $en_modo_edicion_circulacion = true;

    $id_editar_circulacion = intval($_GET['editar_circulacion']);

    $circulacion_data = $modeloCirculacion->obtenerCirculacionPorId($id_editar_circulacion);

    if ($circulacion_data) {
        $c_id                 = $circulacion_data['id_circulacion'];
        $c_codigo_circulacion = $circulacion_data['codigo_circulacion'];
        $c_cilindraje         = $circulacion_data['cilindraje'];
        $c_tonelaje           = $circulacion_data['tonelaje'];
        $c_pasajeros          = $circulacion_data['pasajeros'];
        $c_placa              = $circulacion_data['placa'];
    }
}


if (isset($_GET['eliminar_circulacion'])) {
    $id = intval($_GET['eliminar_circulacion']);
    if ($modeloCirculacion->eliminarCirculacion($id)) {
        header("Location: main.php?page=vehiculos&delete_success=circulacion");
        exit();
    } else {
        echo "<div class='alert alert-danger m-2'>Error al eliminar la circulación.</div>";
    }
}

if (isset($_GET['eliminar_circulacion'])) {
    $id = intval($_GET['eliminar_circulacion']);

    if ($modeloCirculacion->estaEnUso($id)) {
        header("Location: main.php?page=vehiculos&error=circulacion_en_uso");
        exit();
    }

    if ($modeloCirculacion->eliminarCirculacion($id)) {
        header("Location: main.php?page=vehiculos&delete_success=circulacion");
        exit();
    } else {
        echo "<div class='alert alert-danger m-2'>Error al eliminar la circulación.</div>";
    }
}

$listaCirculaciones = $modeloCirculacion->obtenerCirculaciones();


// En este bloque de codigo se muestra un mensaje de éxito si se ha guardado un registro correctamente
if (isset($_GET['save_success']) || isset($_GET['update_success']) || isset($_GET['delete_success'])) {
    if (isset($_GET['save_success'])) {
        $tipo = $_GET['save_success'];
        $mensaje = match ($tipo) {
            'vehiculo' => '¡Vehículo guardado con éxito!',
            'circulacion' => '¡Circulación guardada con éxito!',
            default => '',
        };
    } elseif (isset($_GET['update_success'])) {
        $tipo = $_GET['update_success'];
        $mensaje = match ($tipo) {
            'vehiculo' => '¡Vehículo actualizado con éxito!',
            'circulacion' => '¡Circulación actualizada con éxito!',
            default => '',
        };
    } else {
        $tipo = $_GET['delete_success'];
        $mensaje = match ($tipo) {
            'vehiculo' => '¡Vehículo eliminado con éxito!',
            'circulacion' => '¡Circulación eliminada con éxito!',
            default => '',
        };
    }

    echo '<div id="alertaExito" class="alert alert-success alert-dismissible fade show m-2" role="alert">
            ' . $mensaje . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
          </div>';
}

// En VehiculoController.php, en el método que muestra el formulario de creación
$conductorModel = new Conductor($conexion); // usa tu conexión
$conductores = $conductorModel->obtenerTodos();
$circulaciones = $modeloCirculacion->obtenerCirculaciones(); // obtiene todas las circulaciones

 // ajusta según cómo inicialices tus modelos
//$circulaciones = $modeloVehiculo->obtenerCirculaciones();




require_once __DIR__ .'/../Views/modVehiculo.php';

?>