<?php
/** @var array $listaDeUsuarios */
?>

<?php if (isset($_SESSION['mensaje_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <strong>Correo electrónico inválido</strong><br>
        <span>Correo ya existente.</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    <?php unset($_SESSION['mensaje_error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['mensaje_exito'])): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <?= htmlspecialchars($_SESSION['mensaje_exito']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    <?php unset($_SESSION['mensaje_exito']); ?>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="mb-0"></h4>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary" style="background-color: #6d9773;" data-bs-toggle="modal" data-bs-target="#modalAgregarUsuario">
                <i class="bi bi-plus-lg me-2"></i>Agregar Usuario
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg text-white" style="background-color: #0c3b2e;">Usuarios Registrados</div>
            <div class="card-body table-responsive fadeTable">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            
                            <th>Nombre de Usuario</th>
                            <th>Correo</th>
                            <th>Contraseña</th>
                            <th>Rol</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($listaDeUsuarios) && count($listaDeUsuarios) > 0): ?>
                            <?php foreach ($listaDeUsuarios as $usuario): ?>
                                <tr>
                                    
                                    <td><?= htmlspecialchars($usuario['nombre_usuario'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($usuario['correo'] ?? '') ?></td>
                                    <td><span class="text-muted">********</span></td>                                    
                                    <td><?= htmlspecialchars($usuario['rol'] ?? '') ?></td>
                                    
                                    <td class="text-center">

                                        <div class="btn-group btn-group-sm">
                                            <a href="main.php?page=usuarios&editar_usuario=<?= $usuario['id_usuario'] ?>"
                                            class="btn btn-outline-dark" 
                                            title="Editar">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </a>

                                            <!--<a href="main.php?page=usuarios&eliminar_usuario=<?= $usuario['id_usuario'] ?>" 
                                            class="btn btn-outline-danger" 
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');" 
                                            title="Eliminar">
                                                <i class="bi bi-trash3"></i> Borrar
                                            </a>-->

                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarUsuario<?= $usuario['id_usuario'] ?>" title="Eliminar">
                                                <i class="bi bi-trash3"></i> Borrar
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <!-- Cambiamos el colspan a 6 para que cubra la nueva columna del ID -->
                                <td colspan="6" class="text-center text-muted">No hay usuarios registrados o no se pudieron cargar los datos.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar usuarios -->
<div class="modal fade" id="modalAgregarUsuario" tabindex="-1" aria-labelledby="modalAgregarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg text-white" style="background-color: #0c3b2e;">
                <h5 class="modal-title" id="modalAgregarUsuarioLabel">
                    <?= (isset($en_modo_edicion) && $en_modo_edicion) ? 'Editar usuario' : 'Agregar un nuevo usuario' ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="main.php?page=usuarios" method="POST">
                    <input type="hidden" name="accion" value="guardar">
                    <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($u_id) ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Nombre de Usuario</label>
                        <input type="text" name="nombre_usuario" class="form-control" required value="<?= htmlspecialchars($u_nombre ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control" required 
                            pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$" 
                            title="El correo debe incluir un dominio válido, por ejemplo: usuario@gmail.com" 
                            value="<?= htmlspecialchars($u_correo ?? '') ?>">            
                
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="contrasena" class="form-control" <?= isset($en_modo_edicion) && $en_modo_edicion ? '' : 'required' ?> placeholder="<?= isset($en_modo_edicion) && $en_modo_edicion ? 'Dejar en blanco para no cambiar' : '' ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rol</label>
                        <select name="rol" class="form-select" required>
                            <option value="">Selecciona un rol</option>
                            <option value="Administrador" <?= (isset($u_rol) && $u_rol === 'Administrador') ? 'selected' : '' ?>>Administrador</option>
                            <option value="Supervisor" <?= (isset($u_rol) && $u_rol === 'Supervisor') ? 'selected' : '' ?>>Supervisor</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <?php if (isset($en_modo_edicion) && $en_modo_edicion): ?>
                            <button type="submit" name="editar_usuario" class="btn btn-primary">Actualizar</button>
                        <?php else: ?>
                            <button type="submit" name="agregar_usuario" class="btn btn-primary" style="background-color: #6d9773;">Guardar</button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<?php foreach ($listaDeUsuarios as $usuario): ?>
<div class="modal fade" id="modalEliminarUsuario<?= $usuario['id_usuario'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-exclamation-triangle-fill text-danger display-4 mb-3"></i>
                <h5 class="fw-bold mb-2">¿Seguro que desea borrar usuario?</h5>
                <p class="text-muted small mb-0">Esta acción es irreversible</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                <a href="main.php?page=usuarios&accion=eliminar&eliminar_usuario=<?= $usuario['id_usuario'] ?>" class="btn btn-danger px-4">Sí, eliminar</a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>


<?php if (isset($en_modo_edicion) && $en_modo_edicion): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = new bootstrap.Modal(document.getElementById('modalAgregarUsuario'));
    modal.show();
});
</script>

<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let alertas = document.querySelectorAll('.alert');
    alertas.forEach(function (alerta) {
        setTimeout(function () {
            let bsAlert = new bootstrap.Alert(alerta);
            bsAlert.close();
        }, 3000);
    });
});
</script>