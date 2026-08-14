<?php
ob_start();
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /AutoGest/index.php");
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

$mostrarBienvenida = isset($_SESSION['login_exitoso']) && $_SESSION['login_exitoso'] === true;
$nombreUsuario = $_SESSION['nombre_usuario'] ?? '';

unset($_SESSION['login_exitoso']);

// Si no viene ninguna página en la URL, por defecto cargará 'dashboard'

$permisos = [
    'Administrador' => ['dashboard', 'vehiculos', 'conductores', 'usuarios', 'reportes', 'mantenimiento', 'logout'],
    'SuperAdmin' => ['dashboard', 'vehiculos', 'conductores', 'usuarios', 'reportes', 'mantenimiento', 'logout'],
    'Supervisor' => ['dashboard', 'vehiculos', 'conductores', 'reportes', 'licencia', 'logout'],
];

$rol = $_SESSION['rol'];
$modulosPermitidos = $permisos[$rol] ?? [];


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autogest - Inicio</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!--Con este link se pone el iconito de la camioneta xD-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body>

     <!-- Modal de bienvenida -->
    <!--<div class="modal fade" id="modalBienvenida" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-bienvenida">

            <div class="modal-body text-center">

                <img src="../assets/autogest-logo.png"
                     class="logo-bienvenida mb-3"
                     alt="Logo AutoGest">

                <h3 class="fw-bold">¡Bienvenido!</h3>

                <h5 class="nombre-usuario">
                    <?= htmlspecialchars($nombreUsuario) ?>
                </h5>

                <p class="text-muted">
                    Has iniciado sesión correctamente en
                    <strong>AutoGest</strong>.
                </p>

            </div>

            <div class="modal-footer border-0 justify-content-center pb-4">
                <button
                    type="button"
                    class="btn btn-bienvenida px-5"
                    data-bs-dismiss="modal">
                    Continuar
                </button>
            </div>

            </div>
        </div>
    </div>-->

    <!--Codigo para la animacion de la bienvenida-->
    <?php if ($mostrarBienvenida): ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(document.getElementById('modalBienvenida'));
            modal.show();
        });
    </script>
    <?php endif; ?>

    <!--Codigo para el menu-->
    <div class="sidebar d-flex flex-column" id="sidebarMenu">
        <div class="px-4 mb-4 text-white">
            <img src="../assets/autogest-logo.png" alt="Logo de AutoGest" class="logo">
        </div>

        <div class="nav flex-column w-100">
            <?php if (in_array('dashboard', $modulosPermitidos)): ?>
                <a href="main.php?page=dashboard" class="btn-nav <?= $page == 'dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2-fill me-3 fs-5"></i> <span>Dashboard</span>
                </a>
            <?php endif; ?>

            <?php if (in_array('vehiculos', $modulosPermitidos)): ?>
                <a href="main.php?page=vehiculos" class="btn-nav <?= $page == 'vehiculos' ? 'active' : '' ?>">
                    <i class="bi bi-car-front-fill me-3 fs-5"></i> <span>Vehículos</span>
                </a>
            <?php endif; ?>

            <?php if (in_array('conductores', $modulosPermitidos)): ?>
                <a href="main.php?page=conductores" class="btn-nav <?= $page == 'conductores' ? 'active' : '' ?>">
                    <i class="bi bi-person-fill me-3 fs-5"></i> <span>Conductores</span>
                </a>
            <?php endif; ?>

            
            <?php if (in_array('reportes', $modulosPermitidos)): ?>
                <a href="main.php?page=reportes" class="btn-nav <?= $page == 'reportes' ? 'active' : '' ?>">
                    <i class="bi bi-graph-up-arrow me-3 fs-5"></i> <span>Reportes</span>
                </a>
            <?php endif; ?>
                
            <?php if (in_array('mantenimiento', $modulosPermitidos)): ?>
                    <a href="main.php?page=mantenimiento" class="btn-nav <?= $page == 'mantenimiento' ? 'active' : '' ?>">
                        <i class="bi bi-file-earmark-text-fill me-3 fs-5"></i> <span>Mantenimiento</span>
                    </a>
            <?php endif; ?>
                    
            <?php if (in_array('usuarios', $modulosPermitidos)): ?>
                        <a href="main.php?page=usuarios" class="btn-nav <?= $page == 'usuarios' ? 'active' : '' ?>">
                            <i class="bi bi-people-fill me-3 fs-5"></i> <span>Usuarios</span>
                        </a>
            <?php endif; ?>

            <br>
            <br>
            
            <form id="formLogout" action="../Controllers/LogoutController.php" method="POST" style="display: none;">
            </form>

            <div class="userCard">
                <div class="user-info">
                    <img src="../assets/anadir-contacto.png" alt="Icono de Usuario" class="user-avatar">
                    <div class="user-details">
                        <h4 class="user-name"><?= $_SESSION['nombre_usuario'] ?></h4>
                        <h5 class="user-role"><?= $_SESSION['rol'] ?></h5>
                    </div>
                </div>
                
                <a href="#" class="btn-logout" id="btnLogout" data-bs-toggle="modal" data-bs-target="#modalLogout">
                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </a>
            </div>

        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="modalLogout" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <i class="bi bi-box-arrow-right fs-1 text-danger mb-3"></i>
                    <h5>¿Cerrar sesión?</h5>
                    <p class="text-muted">Tendrás que iniciar sesión de nuevo para continuar.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" id="confirmarLogout" form="formLogout">Sí, cerrar sesión</button>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENEDOR DEL CONTENIDO DINÁMICO -->
    <div class="content-frame" id="contentFrame">
        
        <!-- Botón de barra esquinado (Hamburguesa)-->
        <button class="btn-hamburger" id="toggleMenuBtn" title="Mostrar/Ocultar Menú">
            <i class="bi bi-list"></i>
        </button>
        
        <div id="loaderModulo" class="loader-modulo">
            <div class="loader-escena">
                <i class="fa-solid fa-truck loader-icon"></i>
                <div class="loader-carretera">
                    <div class="loader-linea"></div>
                </div>
            </div>
            <p class="mt-3 text-secondary fw-semibold">Cargando módulo...</p>
        </div>

        <div class="main-content" id="contenidoModulo">
            <?php
            if (!in_array($page, $modulosPermitidos)) {
                echo "<h2>Acceso Denegado</h2>";
                echo "<p>No tienes permisos para acceder a este módulo.</p>";
            } else {
                switch ($page) {
                    case 'dashboard':
                        include 'dashboard.php';
                        break;

                    case 'vehiculos':
                        echo "<h2>Vehículos</h2>";
                        require_once __DIR__ . '/../Controllers/VehiculoController.php';
                        break;

                    case 'conductores':
                        echo "<h2>Conductores</h2>";
                        require_once __DIR__ . '/../Controllers/ConductorController.php';
                        break;

                    case 'usuarios':
                        echo "<h2>Usuarios</h2>";
                        require_once __DIR__ . '/../Controllers/UsuarioController.php';
                        break;

                    case 'reportes':
                        echo "<h2>Reportes</h2>";
                        include 'modreportes.php';
                        break;

                    case 'mantenimiento':
                        echo "<h2>Mantenimiento</h2>";
                        require_once __DIR__ . '/../Controllers/MantenimientoController.php';
                        break;

                    default:
                        if (in_array('dashboard', $modulosPermitidos)) {
                            include 'dashboard.php';
                        } else {
                            echo "<h2>Acceso Denegado</h2>";
                        }
                        break;
                }
            }
            ?>
            </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../scripts/main.js"></script>
</body>
</html>