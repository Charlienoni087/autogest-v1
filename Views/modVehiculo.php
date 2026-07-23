<?php
/** @var array $listaVehiculos */
/** @var array $conductores */
/** @var array $circulaciones */
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../Assets/css/style.css">
    <div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="mb-0"></h4>
        <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" style="background-color: #0c3b2e;" data-bs-toggle="modal" data-bs-target="#modalAgregarVehiculo">
                 <i class="bi bi-plus-lg me-2"></i> Agregar Vehículo
                </button>
                <button type="button" class="btn btn-secondary" style="background-color: #0c3b2e;"data-bs-toggle="modal" data-bs-target="#modalAgregarCirculacion">
            
                    <i class="bi bi-plus-lg me-2"></i> Agregar Circulación
                </button>
                <button type="button" class="btn btn-primary" style="background-color: #0c3b2e;"data-bs-toggle="modal" data-bs-target="#modalListarCirculacion">
            
                    <i class="bi bi-list-ul me-2"></i> Lista de Circulaciones
                </button>

                
                <!--<a href="usuarios.php" class="btn btn-secondary">← Volver a Licencia</a>-->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg text-white" style="background-color: #0c3b2e;">Vehículos Registrados</div>
                <div class="card-body table-responsive fadeTable">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Color</th>
                                <th>Chasis</th>
                                <th>Tipo de Vehículo</th>
                                <th>Combustible</th>
                                <th>Estado</th>
                                <th>N° Póliza</th>
                                <th>Gravamen</th>
                                <th>Conductor</th>
                                <th>Placa</th>
                                <th class="text-center">Acciones</th>
                                

                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($listaVehiculos) && count($listaVehiculos) > 0): ?>
                                <?php foreach ($listaVehiculos as $vehiculo): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($vehiculo['marca']) ?></td>
                                        <td><?= htmlspecialchars($vehiculo['modelo']) ?></td>
                                        <td><?= htmlspecialchars($vehiculo['color']) ?></td>
                                        <td><?= htmlspecialchars($vehiculo['chasis']) ?></td>
                                        <td><?= htmlspecialchars($vehiculo['tipo_vehiculo']) ?></td>
                                        

                                        <td><?= htmlspecialchars($vehiculo['tipo_combustible']) ?></td>
                                        
                                        <td>
                                            <?php if ($vehiculo['estado'] == 1): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($vehiculo['numero_poliza']) ?></td>
                                        <td><?= htmlspecialchars($vehiculo['gravamen']) ?></td>
                                        <td><?= htmlspecialchars($vehiculo['nombre_conductor']) ?></td>
                                        <td><?= htmlspecialchars($vehiculo['placa']) ?></td>
                                        

                                        <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="main.php?page=vehiculos&editar_vehiculo=<?= $vehiculo['id_vehiculo'] ?>"
                                            class="btn btn-outline-dark" 
                                            title="Editar">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </a>

                                            <a href="main.php?page=vehiculos&eliminar_vehiculo=<?= $vehiculo['id_vehiculo'] ?>" 
                                            class="btn btn-outline-danger" 
                                            data-bs-target="#modalAgregarVehiculo" 
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar este vehiculo?');" 
                                            title="Eliminar">
                                                <i class="bi bi-trash3"></i> Borrar
                                            </a>
                                        </div>
                                    </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted">No hay vehiculos registrados o no se pudieron cargar los datos.</td>
                                </tr>
                            <?php endif; ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Modal para agregar vehiculos-->

   <div class="modal fade" id="modalAgregarVehiculo" tabindex="-1" aria-labelledby="modalAgregarVehiculoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="modalAgregarVehiculoLabel">
                    <?= (isset($en_modo_edicion) && $en_modo_edicion) ? 'Editar vehículo' : 'Agregar un nuevo vehículo' ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="main.php?page=vehiculos" method="POST">
                    <input type="hidden" name="id_vehiculo" value="<?= htmlspecialchars($u_id ?? '') ?>">

                    <div class="mb-1">
                        <label class="form-label">Marca</label>
                        <input type="text" name="marca" class="form-control" required placeholder="ej. Toyota" value="<?= htmlspecialchars($u_marca ?? '') ?>">
                    </div>

                    <div class="mb-1">
                        <label class="form-label">Modelo</label>
                        <input type="text" name="modelo" class="form-control" required placeholder="ej. Hilux" value="<?= htmlspecialchars($u_modelo ?? '') ?>">
                    </div>

                    <div class="mb-1">
                        <label class="form-label">Color</label>
                        <input type="text" name="color" class="form-control" required placeholder="ej. Blanco" value="<?= htmlspecialchars($u_color ?? '') ?>">
                    </div>

                    <div class="mb-1">
                        <label class="form-label">Chasis</label>
                        <input type="text" name="chasis" class="form-control" required placeholder="ej. 1234567890" value="<?= htmlspecialchars($u_chasis ?? '') ?>">
                    </div>

                    <div class="mb-1">
                        <label class="form-label">Tipo de Vehículo</label>
                        <input type="text" name="tipo_vehiculo" class="form-control" required placeholder="ej. Camioneta" value="<?= htmlspecialchars($u_tipo_vehiculo ?? '') ?>">
                    </div>

                    <div class="mb-1">
                        <label class="form-label">Combustible</label>
                        <input type="text" name="combustible" class="form-control" required placeholder="ej. Gasolina" value="<?= htmlspecialchars($u_tipo_combustible ?? '') ?>">
                    </div>

                    <div class="mb-1">
                        <label class="form-label">Estado</label>
                        <select name="estado_vehiculo" class="form-select" required>
                            <option value="1" <?= (isset($u_estado) && $u_estado == 1) ? 'selected' : '' ?>>Activo</option>
                            <option value="0" <?= (isset($u_estado) && $u_estado == 0) ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>

                    <div class="mb-1">
                        <label class="form-label">N° Póliza</label>
                        <input type="text" name="numero_poliza" class="form-control" required placeholder="ej. 1234567890" value="<?= htmlspecialchars($u_numero_poliza ?? '') ?>">
                    </div>

                    <div class="mb-1">
                        <label class="form-label">Gravamen</label>
                        <input type="number" name="gravamen" class="form-control" required placeholder="ej. 1000" value="<?= htmlspecialchars($u_gravamen ?? '') ?>">
                    </div>

                    <div class="mb-1">
                        <label class="form-label" for="id_conductor">Conductor</label>
                        <select name="id_conductor" id="id_conductor" class="form-select" required>
                            <option value="">-- Selecciona un conductor --</option>
                            <?php if (isset($conductores) && count($conductores) > 0): ?>
                                <?php foreach ($conductores as $c): ?>
                                    <option value="<?= $c['id_conductor'] ?>" <?= (isset($u_id_conductor) && $u_id_conductor == $c['id_conductor']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['nombre_conductor']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No hay conductores disponibles</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-1">
                        <label class="form-label" for="id_circulacion">Circulación (Placa)</label>
                        <select name="id_circulacion" id="id_circulacion" class="form-select" required>
                            <option value="">-- Selecciona una placa --</option>
                            <?php if (isset($listaCirculaciones) && count($listaCirculaciones) > 0): ?>
                                <?php foreach ($listaCirculaciones as $circ): ?>
                                    <option value="<?= $circ['id_circulacion'] ?>" <?= (isset($u_id_circulacion) && $u_id_circulacion == $circ['id_circulacion']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($circ['placa']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No hay circulaciones disponibles</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <?php if (isset($en_modo_edicion) && $en_modo_edicion): ?>
                            <button type="submit" name="editar_vehiculo" class="btn btn-primary">Actualizar</button>
                        <?php else: ?>
                            <button type="submit" name="agregar_vehiculo" class="btn btn-primary" style="background-color: #6d9773;">Guardar</button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalAgregarCirculacion" tabindex="-1" aria-labelledby="modalAgregarCirculacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="modalAgregarCirculacionLabel">
                    <?= (isset($en_modo_edicion_circulacion) && $en_modo_edicion_circulacion) ? 'Editar circulación' : 'Agregar una nueva circulación' ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="main.php?page=vehiculos" method="POST">
                    <input type="hidden" name="id_circulacion" value="<?= htmlspecialchars($c_id ?? '') ?>">

                    <div class="mb-1">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo_circulacion" class="form-control" required placeholder="ej. " value="<?= htmlspecialchars($c_codigo_circulacion ?? '') ?>">
                    </div>

                    <div class="mb-1">
                        <label class="form-label">Tonelaje</label>
                        <input type="text" name="tonelaje" class="form-control" required placeholder="ej. " value="<?= htmlspecialchars($c_tonelaje ?? '') ?>">
                    </div>

                    <div class="mb-1">
                        <label class="form-label">Cilindraje</label>
                        <input type="text" name="cilindraje" class="form-control" required placeholder="ej. 2000" value="<?= htmlspecialchars($c_cilindraje ?? '') ?>">
                    </div>

                    <div class="mb-1">
                        <label class="form-label">Pasajeros</label>
                        <input type="text" name="pasajeros" class="form-control" required placeholder="ej. 5" value="<?= htmlspecialchars($c_pasajeros ?? '') ?>">
                    </div>

                    <div class="mb-1">
                        <label class="form-label">Placa</label>
                        <input type="text" name="placa" class="form-control" required placeholder="ej. ABC-123" value="<?= htmlspecialchars($c_placa ?? '') ?>">
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <?php if (isset($en_modo_edicion_circulacion) && $en_modo_edicion_circulacion): ?>
                            <button type="submit" name="editar_circulacion" class="btn btn-primary">Actualizar</button>
                        <?php else: ?>
                            <button type="submit" name="agregar_circulacion" class="btn btn-primary" style="background-color: #6d9773;">Guardar</button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal de confirmación para eliminar -->
<?php foreach ($circulaciones as $circulacion): ?>
<div class="modal fade" id="modalEliminarCirculacion<?= $circulacion['id_circulacion'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-exclamation-triangle-fill text-danger display-4 mb-3"></i>
                <h5 class="fw-bold mb-2">¿Seguro que desea borrar la circulación?</h5>
                <p class="text-muted small mb-0">Esta acción es irreversible</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                <a href="main.php?page=circulacion&eliminar_circulacion=<?= $circulacion['id_circulacion'] ?>" class="btn btn-danger px-4">Sí, eliminar</a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<div class="modal fade" id="modalListarCirculacion" tabindex="-1" aria-labelledby="modalAgregarCirculacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                
                <h5 class="modal-title" id="modalAgregarCirculacionLabel">Lista de Circulaciones</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-20">
                        <div class="card shadow-sm">
                            <div class="card-header bg text-white" style="background-color: #0c3b2e;">
                                Circulaciones Registradas
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Código</th>
                                            <th>Tonelaje</th>
                                            <th>Cilindraje</th>
                                            <th>Pasajeros</th>
                                            <th>Placa</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($circulaciones) && count($circulaciones) > 0): ?>
                                            <?php foreach ($circulaciones as $circulacion): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($circulacion['codigo_circulacion']) ?></td>
                                                    <td><?= htmlspecialchars($circulacion['tonelaje']) ?></td>
                                                    <td><?= htmlspecialchars($circulacion['cilindraje']) ?></td>
                                                    <td><?= htmlspecialchars($circulacion['pasajeros']) ?></td>
                                                    <td><?= htmlspecialchars($circulacion['placa']) ?></td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="main.php?page=vehiculos&editar_circulacion=<?= $circulacion['id_circulacion'] ?>"
                                                               class="btn btn-outline-dark"
                                                               title="Editar">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </a>
                                                            <a href="main.php?page=vehiculos&eliminar_circulacion=<?= $circulacion['id_circulacion'] ?>"
                                                               class="btn btn-outline-danger"
                                                               onclick="return confirm('¿Estás seguro de que deseas eliminar esta circulación?');"
                                                               title="Eliminar">
                                                                <i class="bi bi-trash3"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No hay circulaciones registradas</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>                  

        

                       



    <?php if ($en_modo_edicion): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = new bootstrap.Modal(document.getElementById('modalAgregarVehiculo'));
    modal.show();
});
</script>
<?php endif; ?>



 <?php if ($en_modo_edicion_circulacion): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = new bootstrap.Modal(document.getElementById('modalAgregarCirculacion'));
    modal.show();
});
</script>
<script>

</script>
<?php endif; ?>


