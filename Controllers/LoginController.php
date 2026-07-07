<?php

require_once 'models/UsuarioModel.php';

class LoginController {
    private UsuarioModel $modelo;

    public function __construct(mysqli $conexion) {
        $this->modelo = new UsuarioModel($conexion);
    }

    public function procesarLogin(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $usuario = (string) $_POST['usuario'];
            $password = (string) $_POST['password'];

            $usuarioAutenticado = $this->modelo->autenticar($usuario, $password);

            if ($usuarioAutenticado !== null) {
                session_start();
                $_SESSION['usuario_id'] = (int) $usuarioAutenticado['id'];
                $_SESSION['usuario'] = (string) $usuarioAutenticado['usuario'];
                
                header("Location: index.php?action=dashboard");
                exit();
            } else {
                $error = "Usuario o contraseña incorrectos.";
                require_once 'views/login.php';
            }
        } else {
            require_once 'views/login.php';
        }
    }
}
?>