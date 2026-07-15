<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    
    <style>
        body {
            background-color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        
        /* Contenedor del Menú Lateral */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #053c94; 
            padding-top: 30px;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        /* Botones del menú */
        .sidebar .btn-nav {
            background-color: transparent;
            color: #e3e3e4;
            border: none;
            border-radius: 0;
            text-align: left;
            padding: 15px 25px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            display: flex;
            align-items: center;
            transition: background 0.2s, color 0.2s;
            text-decoration: none;
        }

        /* Módulo activo con línea blanca lateral */
        .sidebar .btn-nav.active {
            background-color: rgba(255, 255, 255, 0.12); 
            color: #ffffff;
            border-left: 5px solid #ffffff;
            padding-left: 20px; 
        }

        .sidebar .btn-nav:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        /* Contenedor del Contenido Principal */
        .content-frame {
            margin-left: 260px;
            padding: 20px 30px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Clases para ocultar/desplegar el menú lateral */
        .sidebar.hidden {
            left: -260px;
        }
        .content-frame.expanded {
            margin-left: 0;
        }

        /* Botón de barra esquinado (Menú Hamburguesa) */
        .btn-hamburger {
            background: none;
            border: none;
            font-size: 28px;
            color: #1d6be5; 
            cursor: pointer;
            padding: 0;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
        }
        .btn-hamburger:focus {
            outline: none;
        }
    </style>
</head>
<body>

     
    <div class="sidebar d-flex flex-column" id="sidebarMenu">
        <div class="px-4 mb-4 text-white">
            <h4 class="fw-bold fs-3">Autogest</h4>
            <hr class="text-white-50">
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

        <div class="main-content">
        <?php
        // dependiendo de qué botón presionen, se incluye un archivo diferente

        switch ($page) {
            case 'dashboard':
                include 'dashbord.php';
                break;

            case 'vehiculos':
                echo "<h2>Módulo de Vehículos</h2>"; 
                break;

            case 'licencia':
                echo "<h2>Módulo de Mantenimiento</h2>"; 
                include 'modlicencia.php';
                break;

            case 'conductores':
                echo "<h2>Módulo de Conductores</h2>";
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

    <!-- Script de JavaScript para el comportamiento Desplegable -->
    <script>
        const sidebarMenu = document.getElementById('sidebarMenu');
        const contentFrame = document.getElementById('contentFrame');
        const toggleMenuBtn = document.getElementById('toggleMenuBtn');

        toggleMenuBtn.addEventListener('click', () => {
            // Intercambia las clases para ocultar o mostrar con animación suave
            sidebarMenu.classList.toggle('hidden');
            contentFrame.classList.toggle('expanded');
        });
    </script>
</body>
</html>