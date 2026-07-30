<?php

if (!file_exists(__DIR__ . "/installer/terminado.lock")) {
    header("Location: installer/");
    exit();
}



session_start();
$errorMensaje = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoGest - Iniciar Sesión</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para los iconos de usuario, candado, ojo y escudo -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Public Sans para un acabado idéntico -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/login.css" rel="stylesheet">
</head>

<style>
    
</style>
<body>

    <div class="login-container">
        
        <!-- SECCIÓN IZQUIERDA: Identidad Visual en la parte azul , donde se mmuestra la imagen de la camioneta-->
        <div class="brand-section">
            <div class="brand-content">

                <!-- Contenedor del logotipo real de AutoGest -->
        <div class="brand-logo-container">
            <img src="Assets/autogest-logo.png" class="brand-logo">
        </div>
            
                <div class="brand-title"></div>
                <div class="brand-subtitle"></div>
                
                <div class="">
                
                    <p></p>
                </div>
            </div>
        </div>

        <!-- SECCIÓN DERECHA: Formulario -->
        <div class="form-section">
        

            <div class="login-card text-center">
            
            <div class="login-card text-center">
                
                <h2 class="card-title">Bienvenido</h2>
                <p class="card-subtitle">Inicia sesión para continuar</p>
                
                <!-- Separador con Escudo superior -->
                <div class="divider-shield">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>

                <!-- Para una ventana que te dice que si quieres volver al  Formulario -->
                <form action="Controllers/LoginController.php" method="POST" autocomplete="off">
                    
                    <!-- Campo Correo -->
                    <div class="text-start mb-3">
                        <label for="username" class="form-label">Correo Electronico</label>
                        <div class="input-group-custom">
                            <i class="fa-regular fa-user input-icon"></i>
                            <input type="text" id="username" name="correo" class="form-control-custom" placeholder="Ingresa tu correo" required>
                        </div>
                    </div>
                    
                    <!-- Campo Contraseña -->
                    <div class="text-start mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input type="password" id="password" name="contrasena" class="form-control-custom" placeholder="Ingresa tu contraseña" required>
                            <i class="fa-regular fa-eye toggle-password" onclick="togglePasswordVisibility()"></i>
                        </div>
                    </div>

                    <!-- para el boton que dice Recordarme & Olvidaste Contraseña -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check text-start">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe">
                            <label class="form-check-label text-secondary" style="font-size: 0.85rem;" for="rememberMe">
                                Recordarme
                            </label>
                        </div>
                        <a href="#" class="forgot-password-link">¿Olvidaste tu contraseña?</a>
                    </div>

                    <!-- Para un Botón de Envío Principal -->
                    <button type="submit" class="btn-login-primary">
                        <i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Iniciar Sesión
                    </button>

                    <br>

                    <?php if (!empty($errorMensaje)): ?>
                        <br>
                    <div id="alertaError" class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        <div><?= htmlspecialchars($errorMensaje, ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <?php endif; ?>


                    <!-- Divisor O -->
                    <div class="divider-or">
                        <span>AutoGest - Gestion Vehicular</span>
                    </div>


                </form>
            </div>


    <!-- Bootstrap 5 JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script interactivo para el visor de contraseña (Ojo) -->
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const alerta = document.getElementById("alertaError");

            if (alerta) {
                setTimeout(() => {
                    alerta.classList.add("fade");

                    setTimeout(() => {
                        alerta.remove();
                    }, 150);
                }, 3000);
            }
        });
</script>
</body>
</html>
