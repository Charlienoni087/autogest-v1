<?php
require_once __DIR__ . '/../Config/conexion.php';
require_once __DIR__ . '/../Models/UsuarioModel.php';

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
        $listaDeUsuarios = $this->modelo->obtenerUsuarios();

        $en_modo_edicion = false;
        $u_id = "";
        $u_nombre = "";
        $u_correo = "";
        $u_rol = "";

        if (isset($_GET['editar_usuario'])) {
            $id = intval($_GET['editar_usuario']);
            $usuario = $this->modelo->obtenerPorId($id);

            if ($usuario) {
                $en_modo_edicion = true;
                $u_id = $usuario['id_usuario'];
                $u_nombre = $usuario['nombre_usuario'];
                $u_correo = $usuario['correo'];
                $u_rol = $usuario['rol'];
            }
        }

        require_once __DIR__ . '/../Views/modusuario.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id_usuario'] ?? 0);
            $nombre = trim($_POST['nombre_usuario'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $contrasena = trim($_POST['contrasena'] ?? '');
            $rol = trim($_POST['rol'] ?? '');

            if ($id > 0) {
                $this->modelo->actualizar($id, $nombre, $correo, $contrasena, $rol);
            } else {
                $this->modelo->crear($nombre, $correo, $contrasena, $rol);
            }

            header("Location: /AutoGest/Views/main.php?page=usuarios");
            exit();
        }
    }

    public function eliminar() {
        if (isset($_GET['eliminar_usuario'])) {
            $id = intval($_GET['eliminar_usuario']);
            $this->modelo->eliminar($id);
        }

        header("Location: /AutoGest/Views/main.php?page=usuarios");
        exit();
    }
}

$controlador = new UsuarioController();
$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'index';

if ($accion === 'guardar') {
    $controlador->guardar();
} elseif ($accion === 'eliminar') {
    $controlador->eliminar();
} else {
    $controlador->index();
}
?>