<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUTOGEST - Inicio de Sesión</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* personaliza  el fondo degradado */
        body {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            height: 100vh;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">

    <div class="card shadow p-4" style="width: 100%; max-width: 400px; border-radius: 15px; background-color: #aedaf7;">
        
        <div class="card-body">
            <h2 class="text-center mb-4 text-primary-emphasis fw-bold">AUTOGEST</h2>
            
            <form action="/AUTOGEST/Controllers/Controller.php" method="POST">
                
                <div class="mb-3">
                    <label for="usuario" class="form-label fw-semibold text-secondary-emphasis">Usuario</label>
                    <input type="text" id="usuario" name="nombre_usuario" placeholder="Ingresa tu usuario" class="form-control" required autocomplete="off">
                </div>
                
                <div class="mb-3">
                    <label for="clave" class="form-label fw-semibold text-secondary-emphasis">Contraseña</label>
                    <input type="password" id="clave" name="contrasena" placeholder="Ingresa tu contraseña" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-2 mt-3 fw-bold shadow-sm">
                    Entrar al Sistema
                </button>
            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>