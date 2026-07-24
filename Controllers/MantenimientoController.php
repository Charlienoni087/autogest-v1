<?php
require_once __DIR__ . '/../Config/conexion.php';
require_once __DIR__ . '/../models/mantenimiento.php';
require_once __DIR__ . '/../models/vehiculo.php'; 

class MantenimientoController {
    private $db;
    private $modelo;
    private $modeloVehiculo; 

    public function __construct() {
        global $conexion; 
        $this->db = $conexion;
        $this->modelo = new MantenimientoModel($this->db);
        $this->modeloVehiculo = new Vehiculo($this->db); 
    }

    public function index() {
        $tituloModulo = "Módulo de Mantenimiento";
        $listaMantenimientos = $this->modelo->obtenerMantenimientos();
        $listaVehiculos = $this->modeloVehiculo->obtenerVehiculos(); 
        
        require_once __DIR__ . '/../Views/modMantenimiento.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_vehiculo = $_POST['id_vehiculo'] ?? null;
            $fecha_mantenimiento = $_POST['fecha_mantenimiento'] ?? null;
            $fecha_salida = !empty($_POST['fecha_salida']) ? $_POST['fecha_salida'] : null;
            $descripcion = $_POST['descripcion'] ?? null;
            $costo = $_POST['costo'] ?? 0;
            $estado = $_POST['estado'] ?? 'Pendiente';

            $id_mantenimiento = $_POST['id_mantenimiento'] ?? null;

            if ($id_vehiculo && $fecha_mantenimiento && $descripcion) {
                if (!empty($id_mantenimiento)) {
                    // Sirve para actualizar el registro existente
                    $this->modelo->actualizarMantenimiento($id_mantenimiento, $id_vehiculo, $fecha_mantenimiento, $fecha_salida, $descripcion, $costo, $estado);
                } else {
                    // Si no se viene un id_mantenimiento se sume que es un nuevo registro
                    $this->modelo->insertarMantenimiento($id_vehiculo, $fecha_mantenimiento, $fecha_salida, $descripcion, $costo, $estado);
                }
            }

            header("Location: ../Views/main.php?page=mantenimiento");
            exit();
        }
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->modelo->eliminarMantenimiento($id);
        }
        header("Location: ../Views/main.php?page=mantenimiento");
        exit();
    }
}

// --- ENRUTADOR DEL CONTROLADOR ---
$controlador = new MantenimientoController();
$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'index';

if ($accion === 'guardar') {
    $controlador->guardar();
} elseif ($accion === 'eliminar') {
    $controlador->eliminar();
} else {
    $controlador->index();
}
?>