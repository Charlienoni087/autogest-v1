<?php
// ==========================================
// CONFIGURACIÓN INICIAL E INCLUSIÓN DE CONEXIÓN
// ==========================================
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Buscamos tu archivo en la carpeta 'config' subiendo un nivel desde 'views'
if (!isset($conexion)) {
    include __DIR__ . '/../config/conexion.php';
}

// ==========================================
// 1. LÓGICA PARA INSERTAR UN USUARIO
// ==========================================
if (isset($_POST['guardar_usuario'])) {
    $nombre = $_POST['nombre_usuario'];
    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
    $rol = $_POST['rol'];

    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, correo, contrasena, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $correo, $contrasena, $rol);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success m-2'>¡Usuario guardado con éxito!</div>";
    } else {
        echo "<div class='alert alert-danger m-2'>Error al guardar: " . $conexion->error . "</div>";
    }
    $stmt->close();
}

// ==========================================
// 2. LÓGICA PARA ELIMINAR UN USUARIO
// ==========================================
if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);
    
    $stmt_delete = $conexion->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    $stmt_delete->bind_param("i", $id_eliminar);
    
    if ($stmt_delete->execute()) {
        echo "<div class='alert alert-warning m-2'>¡Usuario eliminado correctamente!</div>";
    } else {
        echo "<div class='alert alert-danger m-2'>Error al eliminar: " . $conexion->error . "</div>";
    }
    $stmt_delete->close();
}

// ==========================================
// 3. LÓGICA PARA ACTUALIZAR UN USUARIO
// ==========================================
if (isset($_POST['actualizar_usuario'])) {
    $id_actualizar = intval($_POST['id_usuario']);
    $nombre = $_POST['nombre_usuario'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
    
    if (!empty($_POST['contrasena'])) {
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
        $stmt_update = $conexion->prepare("UPDATE usuarios SET nombre_usuario=?, correo=?, contrasena=?, rol=? WHERE id_usuario=?");
        $stmt_update->bind_param("ssssi", $nombre, $correo, $contrasena, $rol, $id_actualizar);
    } else {
        $stmt_update = $conexion->prepare("UPDATE usuarios SET nombre_usuario=?, correo=?, rol=? WHERE id_usuario=?");
        $stmt_update->bind_param("sssi", $nombre, $correo, $rol, $id_actualizar);
    }

    if ($stmt_update->execute()) {
        echo "<div class='alert alert-success m-2'>¡Usuario actualizado correctamente!</div>";
    } else {
        echo "<div class='alert alert-danger m-2'>Error al actualizar: " . $conexion->error . "</div>";
    }
    $stmt_update->close();
}

// ==========================================
// 4. CONTROLADOR PARA CARGAR DATOS EN EDICIÓN
// ==========================================
$en_modo_edicion = false;
$u_id = ""; $u_nombre = ""; $u_correo = ""; $u_rol = "";

if (isset($_GET['editar'])) {
    $en_modo_edicion = true;
    $id_editar = intval($_GET['editar']);
    
    $stmt_edit = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmt_edit->bind_param("i", $id_editar);
    $stmt_edit->execute();
    $user_edit_query = $stmt_edit->get_result();

    if ($user_edit_query->num_rows > 0) {
        $user_data = $user_edit_query->fetch_assoc();
        $u_id = $user_data['id_usuario'];
        $u_nombre = $user_data['nombre_usuario'];
        $u_correo = $user_data['correo'];
        $u_rol = $user_data['rol'];
    }
    $stmt_edit->close();
}

// ==========================================
// 5. CARGAR TABLA GENERAL (Usando tu variable $conexion)
// ==========================================
$resultado = $conexion->query("SELECT id_usuario, nombre_usuario, correo, rol FROM usuarios");
?>

<!-- VISTA HTML (Sin etiquetas <html> ni <body> para que se acople limpio a tu main.php) -->
<div class="container-fluid pt-3">
    <div class="row">
        
        <!-- COLUMNA DEL FORMULARIO -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header <?= $en_modo_edicion ? 'bg-warning text-dark' : 'bg-primary text-white' ?> fw-bold">
                    <?= $en_modo_edicion ? 'Modificar Usuario ID: '.htmlspecialchars($u_id) : 'Registrar Nuevo Usuario' ?>
                </div>
                <div class="card-body">
                    <form action="main.php?page=usuarios" method="POST">
                        <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($u_id) ?>">

                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre_usuario" class="form-control" required value="<?= htmlspecialchars($u_nombre) ?>" placeholder="ej. Carlos Gómez Ruiz">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo Institucional</label>
                            <input type="email" name="correo" class="form-control" required value="<?= htmlspecialchars($u_correo) ?>" placeholder="ej. cgomez@alcaldia.gob">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="contrasena" class="form-control" <?= $en_modo_edicion ? '' : 'required' ?> placeholder="<?= $en_modo_edicion ? 'Dejar en blanco para no cambiar' : '********' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol del Sistema</label>
                            <select name="rol" class="form-select" required>
                                <option value="Administrador" <?= $u_rol == 'Administrador' ? 'selected' : '' ?>>Administrador</option>
                                <option value="Supervisor" <?= $u_rol == 'Supervisor' ? 'selected' : '' ?>>Supervisor</option>
                                <option value="Operador" <?= $u_rol == 'Operador' ? 'selected' : '' ?>>Operador</option>
                            </select>
                        </div>
                        
                        <?php if ($en_modo_edicion): ?>
                            <button type="submit" name="actualizar_usuario" class="btn btn-warning w-100 fw-bold mb-2">Actualizar Datos</button>
                            <a href="main.php?page=usuarios" class="btn btn-outline-secondary w-100">Cancelar Edición</a>
                        <?php else: ?>
                            <button type="submit" name="guardar_usuario" class="btn btn-primary w-100">Guardar Usuario</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <!-- Redirección adaptada al menú para las licencias -->
            <a href="main.php?page=licencia" class="btn btn-secondary mt-3 w-100">Ir a Módulo de Licencias →</a>
        </div>

        <!-- COLUMNA DE LA TABLA -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-bold">Registro de Personal Autorizado</div>
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre Completo</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado && $resultado->num_rows > 0): ?>
                                <?php while($row = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id_usuario'] ?></td>
                                    <td><?= htmlspecialchars($row['nombre_usuario']) ?></td>
                                    <td><?= htmlspecialchars($row['correo']) ?></td>
                                    <td>
                                        <?php if($row['rol'] == 'Administrador'): ?>
                                            <span class="badge bg-primary">Administrador</span>
                                        <?php elseif($row['rol'] == 'Supervisor'): ?>
                                            <span class="badge bg-success">Supervisor</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Operador</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="main.php?page=usuarios&editar=<?= $row['id_usuario'] ?>" class="btn btn-outline-dark" title="Editar">✏️ Editar</a>
                                            <a href="main.php?page=usuarios&eliminar=<?= $row['id_usuario'] ?>" class="btn btn-outline-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');" title="Eliminar">🗑️ Borrar</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay usuarios registrados actualmente.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>