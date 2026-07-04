<?php
require_once 'Config/conexion.php'; 

require_once 'controllers/LoginController.php';

$action = isset($_GET['action']) ? (string) $_GET['action'] : 'login';

switch ($action) {
    case 'login':
        $controller = new LoginController($conexion);
        $controller->procesarLogin();
        break;
        
    case 'dashboard':
        session_start();
        if (isset($_SESSION['usuario'])) {
            require_once 'views/dashboard.php';
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;

    case 'logout':
        session_start();
        session_destroy();
        header("Location: index.php?action=login");
        exit();
        
    default:
        echo "<h1>Error 404</h1><p>Página no encontrada.</p>";
        break;
}
?>