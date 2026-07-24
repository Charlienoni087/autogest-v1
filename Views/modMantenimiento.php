<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="h3 text-dark fw-bold">
            <i class="bi bi-wrench-adjustable-circle me-2"></i> Módulo de Mantenimiento
        </h1>
        <button type="button" class="btn text-white" style="background-color: #6d9773;" data-bs-toggle="modal" data-bs-target="#modalAgregarMantenimiento">
            <i class="bi bi-plus-circle me-1"></i> Agregar Mantenimiento
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header text-white fw-semibold" style="background-color: #0c3b2e;">
            <i class="bi bi-table me-2"></i> Historial de Mantenimientos
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="py-3 ps-4">#</th>
                            <th class="py-3">Vehículo</th>
                            <th class="py-3">F. Entrada</th>
                            <th class="py-3">F. Salida</th>
                            <th class="py-3">Descripción</th>
                            <th class="py-3">Costo</th>
                            <th class="py-3">Estado</th>
                            <th class="py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($listaMantenimientos)): ?>
                            <?php foreach ($listaMantenimientos as $index => $m): ?>
                                <tr>
                                    <td class="ps-4"><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($m['marca'] . ' ' . $m['modelo']); ?></td>
                                    <td><?php echo htmlspecialchars($m['fecha_mantenimiento']); ?></td>
                                    <td><?php echo !empty($m['fecha_salida']) ? htmlspecialchars($m['fecha_salida']) : '<span class="text-muted">En taller</span>'; ?></td>
                                    <td><?php echo htmlspecialchars($m['descripcion']); ?></td>
                                    <td>$<?php echo number_format($m['costo'], 2); ?></td>
                                    <td>
                                        <?php 
                                            $badgeBg = 'bg-warning text-dark';
                                            if ($m['estado'] === 'Completado') $badgeBg = 'bg-success';
                                            if ($m['estado'] === 'En Proceso') $badgeBg = 'bg-info text-dark';
                                        ?>
                                        <span class="badge <?php echo $badgeBg; ?>">
                                            <?php echo htmlspecialchars($m['estado']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <!-- Botón Editar que rellena el modal -->
                                        <button class="btn btn-sm btn-outline-primary me-1 btn-editar" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalAgregarMantenimiento"
                                                data-id="<?php echo $m['id_mantenimiento']; ?>"
                                                data-vehiculo="<?php echo $m['id_vehiculo']; ?>"
                                                data-entrada="<?php echo $m['fecha_mantenimiento']; ?>"
                                                data-salida="<?php echo $m['fecha_salida']; ?>"
                                                data-descripcion="<?php echo htmlspecialchars($m['descripcion'], ENT_QUOTES); ?>"
                                                data-costo="<?php echo $m['costo']; ?>"
                                                data-estado="<?php echo $m['estado']; ?>"
                                                title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <!-- Botón Eliminar -->
                                        <a href="main.php?page=mantenimiento&accion=eliminar&id=<?php echo $m['id_mantenimiento']; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('¿Estás seguro de eliminar este registro de mantenimiento?');" 
                                           title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No hay registros de mantenimiento disponibles.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalAgregarMantenimiento" tabindex="-1" aria-labelledby="modalMantenimientoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #0c3b2e;">
                <h5 class="modal-title" id="modalMantenimientoLabel">
                    <i class="bi bi-plus-circle me-1"></i> <span id="modalTituloText">Registrar un nuevo mantenimiento</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="main.php?page=mantenimiento&accion=guardar" method="POST">
                <!-- Campo oculto para manejar el ID cuando se edita -->
                <input type="hidden" id="id_mantenimiento" name="id_mantenimiento" value="">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_vehiculo" class="form-label fw-semibold">Vehículo</label>
                        <select class="form-select" id="id_vehiculo" name="id_vehiculo" required>
                            <option value="" selected disabled>Seleccione un vehículo...</option>
                            <?php 
                            if (!empty($listaVehiculos)) {
                                foreach ($listaVehiculos as $vehiculo) {
                                    echo '<option value="' . $vehiculo['id_vehiculo'] . '">' . htmlspecialchars($vehiculo['marca'] . ' ' . $vehiculo['modelo'] . ' (Chasis: ' . $vehiculo['chasis'] . ')') . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_mantenimiento" class="form-label fw-semibold">Fecha de Entrada</label>
                            <input type="date" class="form-control" id="fecha_mantenimiento" name="fecha_mantenimiento" required value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_salida" class="form-label fw-semibold">Fecha de Salida </label>
                            <input type="date" class="form-control" id="fecha_salida" name="fecha_salida">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Ej. Cambio de aceite, revisión de frenos..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="costo" class="form-label fw-semibold">Costo ($)</label>
                        <input type="number" step="0.01" class="form-control" id="costo" name="costo" placeholder="0.00" required>
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label fw-semibold">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En Proceso">En Proceso</option>
                            <option value="Completado">Completado</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn text-white" style="background-color: #6d9773;">Guardar Mantenimiento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- sirve para manejar la edición y limpieza del modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalMantenimiento = document.getElementById('modalAgregarMantenimiento');
    
    modalMantenimiento.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget; //
        
        // para el boton de editar llena los campos
        if (button && button.classList.contains('btn-editar')) {
            document.getElementById('modalTituloText').textContent = "Editar Mantenimiento";
            document.getElementById('id_mantenimiento').value = button.getAttribute('data-id');
            document.getElementById('id_vehiculo').value = button.getAttribute('data-vehiculo');
            document.getElementById('fecha_mantenimiento').value = button.getAttribute('data-entrada');
            document.getElementById('fecha_salida').value = button.getAttribute('data-salida');
            document.getElementById('descripcion').value = button.getAttribute('data-descripcion');
            document.getElementById('costo').value = button.getAttribute('data-costo');
            document.getElementById('estado').value = button.getAttribute('data-estado');
        } else { 
            document.getElementById('modalTituloText').textContent = "Registrar un nuevo mantenimiento";
            document.getElementById('id_mantenimiento').value = '';
            document.getElementById('id_vehiculo').value = '';
            document.getElementById('fecha_mantenimiento').value = '<?php echo date('Y-m-d'); ?>';
            document.getElementById('fecha_salida').value = '';
            document.getElementById('descripcion').value = '';
            document.getElementById('costo').value = '';
            document.getElementById('estado').value = 'Pendiente';
        }
    });
});
</script>