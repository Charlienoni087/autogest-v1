<?php
ob_start();
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /AutoGest/index.php");
    exit;
}

$mostrarBienvenida = isset($_SESSION['login_exitoso']) && $_SESSION['login_exitoso'] === true;
$nombreUsuario = $_SESSION['nombre_usuario'] ?? '';

unset($_SESSION['login_exitoso']);

// Si no viene ninguna página en la URL, por defecto cargará 'dashboard'
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';



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
    <div class="modal fade" id="modalBienvenida" tabindex="-1" aria-hidden="true">
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
    </div>

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

            <a href="main.php?page=dashboard" class="btn-nav <?php echo $page == 'dashboard' ? 'active' : ''; ?>">
                <i class="bi bi-grid-1x2-fill me-3 fs-5"></i> <span>Dashboard</span>
            </a>
            <a href="main.php?page=vehiculos" class="btn-nav <?php echo $page == 'vehiculos' ? 'active' : ''; ?>">
                <i class="bi bi-car-front-fill me-3 fs-5"></i> <span>Vehículos</span>
            </a>
            <a href="main.php?page=conductores" class="btn-nav <?php echo $page == 'conductores' ? 'active' : ''; ?>">
                <i class="bi bi-people-fill me-3 fs-5"></i> <span>Conductores</span>
            </a>
            <a href="main.php?page=usuarios" class="btn-nav <?php echo $page == 'usuarios' ? 'active' : ''; ?>">
                <i class="bi bi-person-badge me-3 fs-5"></i> <span>Usuarios</span>
            </a>
            <a href="main.php?page=reportes" class="btn-nav <?php echo $page == 'reportes' ? 'active' : ''; ?>">
                <i class="bi bi-bar-chart-line-fill me-3 fs-5"></i> <span>Reportes</span>
            </a>
            <a href="main.php?page=licencia" class="btn-nav <?php echo $page == 'licencia' ? 'active' : ''; ?>">
                <i class="bi bi-card-text me-3 fs-5"></i> <span>Mantenimiento</span>
            </a>
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
        // dependiendo de qué botón presionen, se incluye un archivo diferente

        switch ($page) {
            case 'dashboard':
                include 'dashbord.php';
                break;

            case 'vehiculos':
                echo "<h2>Módulo de Vehículos</h2>"; 
                include 'modVehiculo.php';
                break;

            case 'licencia':
                echo "<h2>Módulo de Mantenimiento</h2>"; 
                include 'modMantenimiento.php';
                break;

            case 'conductores':
                echo "<h2>Módulo de Conductores</h2>";
                require_once __DIR__ . '/../Controllers/ConductorController.php';
                break;

            case 'usuarios':
                echo "<h2>Módulo de Usuarios</h2>"; 
                include 'modusuario.php'; //  
                break;

            case 'reportes':
                echo "<h2>Módulo de Reportes</h2>";
                include 'modreportes.php';
                break;

            default:
                include 'dashboard.php';
                break;
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../scripts/main.js"></script>
</body>
</html>