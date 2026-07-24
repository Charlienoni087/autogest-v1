<?php
require_once __DIR__ . '/../Config/conexion.php';
require_once __DIR__ . '/../models/UsuarioModel.php'; 

class UsuarioController {
    private $db;
    private $modelo;

    public function __construct() {
        global $conexion; 
        $this->db = $conexion;
        $this->modelo = new UsuarioModel($this->db);
    }

    public function index() {
        $tituloModulo = "Módulo de Usuarios";
        
        // Se consulta la lista de usuarios para enviarla a la vista modusuario.php
        $listaUsuarios = $this->modelo->obtenerUsuarios(); 

        require_once __DIR__ . '/../Views/modusuario.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header("Location: ../Views/main.php?page=usuarios");
            exit();
        }
    }
}

// --- ENRUTADOR DEL CONTROLADOR ---
$controlador = new UsuarioController();
$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'index';

if ($accion === 'guardar') {
    $controlador->guardar();
} else {
    $controlador->index();
}
?>