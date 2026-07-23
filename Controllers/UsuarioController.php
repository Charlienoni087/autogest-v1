<?php
require_once __DIR__.'/../config/conexion.php';
require_once __DIR__.'/../Models/UsuarioModel.php';

$modeloUsuario = new UsuarioModel($conexion);

// 1. Inicializar variables para la vista (Modo edición y valores vacíos por defecto)
$en_modo_edicion = false;
$u_id = ""; $u_nombre = ""; $u_correo = ""; $u_rol = "";

// 2. PROCESAR FORMULARIOS (Agregar y Editar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_usuario'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $rol = trim($_POST['rol'] ?? '');

    // Validaciones
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo debe ser válido e incluir '@'.";
    } elseif (isset($_POST['agregar_usuario']) && empty(trim($contrasena))) {
        $error = "La contraseña no puede estar vacía.";
    } else {
        // Ejecutar acción según el botón presionado
        if (isset($_POST['agregar_usuario'])) {
            // Se usa el nuevo método crear()
            if ($modeloUsuario->crear($nombre, $correo, $contrasena, $rol)) {
                header("Location: main.php?page=usuarios&accion=creado");
                exit();
            } else {
                $error = "Error al guardar el usuario en la base de datos.";
            }
        } elseif (isset($_POST['editar_usuario'])) {
            $id = intval($_POST['id_usuario']);
            // Se usa el nuevo método actualizar()
            if ($modeloUsuario->actualizar($id, $nombre, $correo, $contrasena, $rol)) {
                header("Location: main.php?page=usuarios&accion=editado");
                exit();
            } else {
                $error = "Error al actualizar el usuario en la base de datos.";
            }
        }
    }
}

// 3. ELIMINAR USUARIO
if (isset($_GET['eliminar_usuario'])) {
    $id = intval($_GET['eliminar_usuario']);
    // Se usa el nuevo método eliminar()
    if ($modeloUsuario->eliminar($id)) {
        header("Location: main.php?page=usuarios&accion=eliminado");
        exit();
    } else {
        $error = "Error al eliminar el usuario.";
    }
}

// 4. CARGAR DATOS PARA MODO EDICIÓN
if (isset($_GET['editar_usuario'])) {
    $id_editar = intval($_GET['editar_usuario']);
    // Se usa el nuevo método obtenerPorId()
    $datosUsuario = $modeloUsuario->obtenerPorId($id_editar);

    if ($datosUsuario) {
        $en_modo_edicion = true;
        $u_id     = $datosUsuario['id_usuario'];
        $u_nombre = $datosUsuario['nombre_usuario'];
        $u_correo = $datosUsuario['correo'];
        $u_rol    = $datosUsuario['rol'];
    }
}

// 5. OBTENER LISTA DE USUARIOS (Para la tabla)
// Se usa el nuevo método obtenerTodos()
$listaUsuarios = $modeloUsuario->obtenerTodos();

// 6. MOSTRAR MENSAJES DE ÉXITO O ERROR
if (isset($_GET['accion'])) {
    $mensajes = [
        'creado' => '¡Usuario guardado con éxito!',
        'editado' => '¡Usuario actualizado con éxito!',
        'eliminado' => '¡Usuario eliminado con éxito!'
    ];
    
    $accion = $_GET['accion'];
    if (isset($mensajes[$accion])) {
        echo '<div id="alertaExito" class="alert alert-success alert-dismissible fade show m-2" role="alert">
                ' . $mensajes[$accion] . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>';
    }
}

if (isset($error)) {
    echo "<div class='alert alert-danger alert-dismissible fade show m-2'>
            {$error}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Cerrar'></button>
            </div>";
}

require_once __DIR__ .'/../Views/modusuario.php';
?>