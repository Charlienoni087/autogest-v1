<?php
/** @var array $listaConductores */
/** @var array $listaLicencias */
$categorias_guardadas = !empty($u_categorias) ? explode(',', $u_categorias) : [];
?>
<div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0"></h4>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" style="background-color: #6d9773;" data-bs-toggle="modal" data-bs-target="#modalAgregarConductor">
                    Agregar Conductor
                </button>
            </div>
        </div>
    </div>

<div class="row">
        <div class="col-md-16">
            <div class="card shadow-sm">
                <div class="card-header bg text-white" style="background-color: #0c3b2e;">Conductores Registrados</div>
                <div class="card-body table-responsive fadeTable">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Cedula</th>
                                <th>Telefono</th>
                                <th>Tipo de Sangre</th>
                                <th>Estado</th>
                                <th>N° Licencia</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                       <tbody>
                            <?php if (isset($listaConductores) && count($listaConductores) > 0): ?>
                                <?php foreach ($listaConductores as $conductor): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($conductor['nombre_conductor']) ?></td>
                                        <td><?= htmlspecialchars($conductor['cedula']) ?></td>
                                        <td><?= htmlspecialchars($conductor['telefono']) ?></td>
                                        <td><?= htmlspecialchars($conductor['tipo_sangre']) ?></td>
                                        <td>
                                            <?php if ($conductor['estado'] == 1): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($conductor['numero_licencia'] ?? 'Sin licencia') ?></td>

                                        <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="main.php?page=conductores&editar_conductor=<?= $conductor['id_conductor'] ?>"
                                            class="btn btn-outline-dark" 
                                            title="Editar">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </a>

                                            <a href="main.php?page=conductores&eliminar_conductor=<?= $conductor['id_conductor'] ?>" 
                                            class="btn btn-outline-danger" 
                                            data-bs-target="#modalAgregarConductor" 
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar este conductor?');" 
                                            title="Eliminar">
                                                <i class="bi bi-trash3"></i> Borrar
                                            </a>
                                        </div>
                                    </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No hay conductores registrados o no se pudieron cargar los datos.</td>
                                </tr>
                            <?php endif; ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <br>
    <br>
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Licencias</h4>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" style="background-color: #6d9773;" data-bs-toggle="modal" data-bs-target="#modalAgregarLicencia">
                    Agregar Licencia
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-16">
            <div class="card shadow-sm">
                <div class="card-header bg text-white" style="background-color: #0c3b2e;">Licencias de Conducir Registradas</div>
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>N° Licencia</th>
                                <th>Tipo</th>
                                <th>Categorías</th>
                                <th>Vencimiento</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($listaLicencias) && is_array($listaLicencias) && count($listaLicencias) > 0): ?>
                                <?php foreach ($listaLicencias as $licencia): ?>
                                    <tr>
                                       
                                        <td><?= htmlspecialchars($licencia['numero_licencia']) ?></td>
                                        <td><?= htmlspecialchars($licencia['tipo_licencia']) ?></td>
                                        <td><?= htmlspecialchars($licencia['categorias']) ?></td>
                                        <td><?= htmlspecialchars($licencia['fecha_vencimiento']) ?></td>

                                        <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="main.php?page=conductores&editar_licencia=<?= $licencia['id_licencia'] ?>"
                                            class="btn btn-outline-dark" 
                                            title="Editar">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </a>

                                            <a href="main.php?page=conductores&eliminar_licencia=<?= $licencia['id_licencia'] ?>" 
                                            class="btn btn-outline-danger" 
                                            data-bs-target="#modalAgregarLicencia" 
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar esta licencia?');" 
                                            title="Eliminar">
                                                <i class="bi bi-trash3"></i> Borrar
                                            </a>
                                        </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay licencias registradas o no se pudieron cargar los datos.</td>
                                </tr>
                            <?php endif; ?>
                                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Modal para agregar conductores-->

    <div class="modal fade" id="modalAgregarConductor" tabindex="-1" aria-labelledby="modalAgregarLicenciaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg text-white" style="background-color: #0c3b2e;">
                    <h5 class="modal-title" id="modalAgregarConductorLabel">Agregar un nuevo conductor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form action="main.php?page=conductores" method="POST">
                        <input type="hidden" name="id_conductor" value="<?= htmlspecialchars($u_id) ?>">
                        <div class="mb-1">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre_conductor" class="form-control" required value="<?= htmlspecialchars($u_nombre) ?>">
                        </div>

                        <div class="mb-1">
                            <label class="form-label">N° Cédula</label>
                            <input type="text" name="numero_cedula" class="form-control" required value="<?= htmlspecialchars($u_cedula) ?>">
                        </div>

                        <div class="mb-1">
                            <label class="form-label">Telefono</label>
                            <input type="text" name="telefono" class="form-control" required value="<?= htmlspecialchars($u_telefono) ?>">
                        </div>

                        <div class="mb-1">
                            <label class="form-label">Tipo de Sangre</label>
                            <select name="tipo_sangre" class="form-select" required>
                                <option value="A+" <?= $u_tipo_sangre === 'A+' ? 'selected' : '' ?>>A+</option>
                                <option value="A-" <?= $u_tipo_sangre === 'A-' ? 'selected' : '' ?>>A-</option>
                                <option value="B+" <?= $u_tipo_sangre === 'B+' ? 'selected' : '' ?>>B+</option>
                                <option value="B-" <?= $u_tipo_sangre === 'B-' ? 'selected' : '' ?>>B-</option>
                                <option value="AB+" <?= $u_tipo_sangre === 'AB+' ? 'selected' : '' ?>>AB+</option>
                                <option value="AB-" <?= $u_tipo_sangre === 'AB-' ? 'selected' : '' ?>>AB-</option>
                                <option value="O+" <?= $u_tipo_sangre === 'O+' ? 'selected' : '' ?>>O+</option>
                                <option value="O-" <?= $u_tipo_sangre === 'O-' ? 'selected' : '' ?>>O-</option>
                            </select>
                        </div>

                        <div class="mb-1">
                            <label class="form-label">Estado</label>
                            <select name="estado_conductor" class="form-select" required>
                                <option value="1" <?= $u_estado == 1 ? 'selected' : '' ?>>Activo</option>
                                <option value="0" <?= $u_estado == 0 ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                        
                        <div class="mb-1">
                            <label class="form-label">Licencia</label>
                            <select name="id_licencia" class="form-select" required>
                                <?php foreach ($listaLicencias as $licencia): ?>
                                    <option value="<?= $licencia['id_licencia'] ?>" <?= $u_id_licencia == $licencia['id_licencia'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($licencia['numero_licencia']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <?php if ($en_modo_edicion): ?>
                                <button type="submit" name="editar_conductor" class="btn btn-primary">Actualizar</button>
                            <?php else: ?>
                                <button type="submit" name="agregar_conductor" class="btn btn-primary" style="background-color: #6d9773;">Guardar</button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    
    <!--Modal para agregar licencias-->
    <div class="modal fade" id="modalAgregarLicencia" tabindex="-1" aria-labelledby="modalAgregarLicenciaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg text-white" style="background-color: #0c3b2e;">
                    <h5 class="modal-title" id="modalAgregarLicenciaLabel">Agregar nueva licencia</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form action="main.php?page=conductores" method="POST">
                        <input type="hidden" name="id_licencia" value="<?= htmlspecialchars($u_id_lic) ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Número de Licencia</label>
                            <input type="text" name="numero_licencia" class="form-control" required value="<?= htmlspecialchars($u_numero) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo de Licencia</label>
                            <select name="tipo_licencia" class="form-select" required>
                                <option value="Ordinaria" <?= $u_tipo === 'Ordinaria' ? 'selected' : '' ?>>Ordinaria</option>
                                <option value="Profesional" <?= $u_tipo === 'Profesional' ? 'selected' : '' ?>>Profesional</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Categorías</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="1" <?= in_array('1', $categorias_guardadas) ? 'checked' : '' ?>> <label class="form-check-label">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="2" <?= in_array('2', $categorias_guardadas) ? 'checked' : '' ?>> <label class="form-check-label">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="3" <?= in_array('3', $categorias_guardadas) ? 'checked' : '' ?>> <label class="form-check-label">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="4A" <?= in_array('4A', $categorias_guardadas) ? 'checked' : '' ?>> <label class="form-check-label">4A</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="4B" <?= in_array('4B', $categorias_guardadas) ? 'checked' : '' ?>> <label class="form-check-label">4B</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="5A" <?= in_array('5A', $categorias_guardadas) ? 'checked' : '' ?>> <label class="form-check-label">5A</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="5B" <?= in_array('5B', $categorias_guardadas) ? 'checked' : '' ?>> <label class="form-check-label">5B</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="6A" <?= in_array('6A', $categorias_guardadas) ? 'checked' : '' ?>> <label class="form-check-label">6A</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="6B" <?= in_array('6B', $categorias_guardadas) ? 'checked' : '' ?>> <label class="form-check-label">6B</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="7" <?= in_array('7', $categorias_guardadas) ? 'checked' : '' ?>> <label class="form-check-label">7</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="8" <?= in_array('8', $categorias_guardadas) ? 'checked' : '' ?>> <label class="form-check-label">8</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha de Vencimiento</label>
                            <input type="date" name="fecha_vencimiento" class="form-control" required value="<?= htmlspecialchars($u_fecha_vencimiento) ?>">
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <?php if ($en_modo_edicion_lic): ?>
                                <button type="submit" name="editar_licencia" class="btn btn-primary">Actualizar</button>
                            <?php else: ?>
                                <button type="submit" name="guardar_licencia" class="btn btn-primary" style="background-color: #6d9773;">Guardar</button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php if ($en_modo_edicion): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = new bootstrap.Modal(document.getElementById('modalAgregarConductor'));
    modal.show();
});
</script>
<?php endif; ?>

<?php if ($en_modo_edicion_lic): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = new bootstrap.Modal(document.getElementById('modalAgregarLicencia'));
    modal.show();
});
</script>
<script>

</script>
<?php endif; ?>
