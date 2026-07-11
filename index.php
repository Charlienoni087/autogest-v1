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
    <link rel="stylesheet" href="assets/css/index.css">
</head>

<style>
    body {
            font-family: 'Public Sans', sans-serif;
            background-color: #f4f6f9;
            height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        /* Contenedor principal de la pantalla dividida */
        .login-container {
            height: 100vh;
            display: flex;
        }

        /*SECCIÓN IZQUIERDA*/
        .brand-section {
            width: 44%;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #ffffff;
            padding: 40px;
            text-align: center;

            /* Imagen de la camioneta para el fondo */
            background: url('Camioneta.jpg') no-repeat center center;
            background-size: cover;
            overflow: hidden;
        }

        /* Capa azul semitransparente */
        .brand-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;

            /* Color Azul oscuro que muestra transparencia en el lado izquierdo*/
            background: linear-gradient(rgba(10, 34, 64, 0.93), rgba(10, 34, 64, 0.93));
            z-index: 1;
        }

        /* Contenedor interno para que todo el contenido quede POR ENCIMA de la transparencia */
        .brand-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
        }


        .brand-title {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .brand-subtitle {
            font-size: 1.1rem;
            font-weight: 500;
            letter-spacing: 1px;
            color: #d1dbe5;
            margin-bottom: 40px;
            text-transform: uppercase;
        }

        .brand-footer {
            margin-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 15px;
            width: 65%;
        }

        .brand-footer i {
            font-size: 1.3rem;
            margin-bottom: 8px;
            display: block;
        }

        .brand-footer p {
            font-size: 0.85rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0;
        }

        /*SECCIÓN DERECHA: FORMULARIO DE LOGIN*/
        .form-section {
            width: 56%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            background: #f8f9fa;
            position: relative;
        }

        /* Tarjeta de Login (cuadro blanco) */
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            width: 100%;
            max-width: 480px;
            padding: 45px 40px;
        }

        .card-title {
            color: #1e293b;
            font-weight: 700;
            font-size: 1.6rem;
            margin-bottom: 6px;
        }

        .card-subtitle {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 25px;
        }

        /*Decoraciones internass en el login */
        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-custom i.input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            z-index: 10;
        }

        .input-group-custom i.toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            z-index: 10;
        }

        .form-control-custom {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #334155;
            transition: all 0.3s;
        }

        .form-control-custom:focus {
            border-color: #0a2240;
            box-shadow: 0 0 0 3px rgba(10, 34, 64, 0.1);
            outline: none;
        }

        /* Checkbox y Enlaces */
        .form-check-input:checked {
            background-color: #0a2240;
            border-color: #0a2240;
        }

        .forgot-password-link {
            color: #1d4ed8;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .forgot-password-link:hover {
            text-decoration: underline;
        }

        /* Botón de inicio sesión */
        .btn-login-primary {
            background-color: #0a2240;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
            font-size: 1rem;
            width: 100%;
            transition: background-color 0.2s;
            margin-top: 10px;
        }

        .btn-login-primary:hover {
            background-color: #07172c;
            color: #ffffff;
        }

        /*Otros decorativos */
        .divider-shield {
            display: flex;
            align-items: center;
            text-align: center;
            color: #cbd5e1;
            margin-bottom: 25px;
        }
        .divider-shield::before, .divider-shield::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }
        .divider-shield i {
            padding: 0 10px;
            color: #94a3b8;
            font-size: 1rem;
        }

        .divider-or {
            display: flex;
            align-items: center;
            text-align: center;
            color: #94a3b8;
            margin: 20px 0;
            font-size: 0.85rem;
        }
        .divider-or::before, .divider-or::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }
        .divider-or span {
            padding: 0 10px;
        }


        .btn-login-secondary:hover {
            background-color: #f8f9fa;
            border-color: #94a3b8;
        }

        .btn-login-secondary i {
            color: #0a2240;
        }


        /* Para que se muestre las 2 divisiones */
        @media (max-width: 992px) {
            .brand-section {
                display: none;
            }
            .form-section {
                width: 100%;
            }
        }
</style>
<body>

    <div class="login-container">
        
        <!-- SECCIÓN IZQUIERDA: Identidad Visual en la parte azul , donde se mmuestra la imagen de la camioneta-->
        <div class="brand-section">
            <div class="brand-content">
            
                <div class="brand-title">AUTOGEST</div>
                <div class="brand-subtitle">Sistema de Inventario de Vehículos</div>
                
                <div class="brand-footer">
                    <i class="fa-solid fa-building-columns"></i>
                    <p>Alcaldía Municipal</p>
                </div>
            </div>
        </div>

        <!-- SECCIÓN DERECHA: Formulario -->
        <div class="form-section">
            
            <div class="login-card text-center">
                <h2 class="card-title">Bienvenido</h2>
                <p class="card-subtitle">Inicia sesión para continuar</p>
                
                <!-- Separador con Escudo superior -->
                <div class="divider-shield">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>

                <!-- Para una ventana que te dice que si quieres volver al  Formulario -->
<form action="Controllers/Controller.php" method="POST" autocomplete="off">
                    
                    <!-- Campo Usuario -->
                    <div class="text-start mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <div class="input-group-custom">
                            <i class="fa-regular fa-user input-icon"></i>
                            <input type="text" id="username" name="nombre_usuario" class="form-control-custom" placeholder="Ingresa tu usuario" required>
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

                    <!-- Divisor O -->
                    <div class="divider-or">
                        <span>o</span>
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
</body>
</html>
