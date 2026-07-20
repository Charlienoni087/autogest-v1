<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($conexion)) {
    include __DIR__ . '/../Config/conexion.php';
}

$mensaje = '';
$tipo_mensaje = '';
$en_modo_edicion = false;
$r_id = $r_fecha = $r_hora_entrada = $r_hora_salida = $r_id_conductor = $r_id_vehiculo = '';
$f_fecha_inicio = $f_fecha_fin = $f_id_conductor = $f_id_vehiculo = '';
$listaReportes = $listaConductores = $listaVehiculos = [];

require_once __DIR__ . '/../Controllers/ReporteController.php';

// Query string con los filtros activos, para reutilizarlo en los links de exportar
$query_filtros = http_build_query([
    'fecha_inicio' => $f_fecha_inicio,
    'fecha_fin' => $f_fecha_fin,
    'f_id_conductor' => $f_id_conductor,
    'f_id_vehiculo' => $f_id_vehiculo,
]);
?>

<div class="container-fluid pt-3">

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?= $tipo_mensaje ?> m-2"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- COLUMNA DEL FORMULARIO -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header <?= $en_modo_edicion ? 'bg-warning text-dark' : 'bg-primary text-white' ?> fw-bold">
                    <?= $en_modo_edicion ? 'Modificar Reporte ID: ' . htmlspecialchars($r_id) : 'Registrar Nuevo Reporte' ?>
                </div>
                <div class="card-body">
                    <form action="main.php?page=reportes" method="POST">
                        <input type="hidden" name="id_reporte" value="<?= htmlspecialchars($r_id) ?>">

                        <div class="mb-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" name="fecha" class="form-control" required value="<?= htmlspecialchars($r_fecha) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hora de Entrada</label>
                            <input type="time" name="hora_entrada" class="form-control" required value="<?= htmlspecialchars($r_hora_entrada) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hora de Salida</label>
                            <input type="time" name="hora_salida" class="form-control" required value="<?= htmlspecialchars($r_hora_salida) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Conductor</label>
                            <select name="id_conductor" class="form-select" required>
                                <option value="">-- Selecciona un conductor --</option>
                                <?php foreach ($listaConductores as $c): ?>
                                    <option value="<?= $c['id_conductor'] ?>" <?= $r_id_conductor == $c['id_conductor'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['nombre_conductor']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Vehículo</label>
                            <select name="id_vehiculo" class="form-select" required>
                                <option value="">-- Selecciona un vehículo --</option>
                                <?php foreach ($listaVehiculos as $v): ?>
                                    <option value="<?= $v['id_vehiculo'] ?>" <?= $r_id_vehiculo == $v['id_vehiculo'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($v['marca'] . ' ' . $v['modelo'] . ' - ' . $v['placa']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php if ($en_modo_edicion): ?>
                            <button type="submit" name="actualizar_reporte" class="btn btn-warning w-100 fw-bold mb-2">Actualizar Reporte</button>
                            <a href="main.php?page=reportes" class="btn btn-outline-secondary w-100">Cancelar Edición</a>
                        <?php else: ?>
                            <button type="submit" name="guardar_reporte" class="btn btn-primary w-100">Guardar Reporte</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- COLUMNA DE FILTROS + TABLA -->
        <div class="col-md-8">

            <!-- FILTROS DE BÚSQUEDA -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light fw-bold">Filtrar Reportes</div>
                <div class="card-body">
                    <form action="main.php" method="GET" class="row g-2 align-items-end">
                        <input type="hidden" name="page" value="reportes">
                        <div class="col-md-3">
                            <label class="form-label small">Desde</label>
                            <input type="date" name="fecha_inicio" class="form-control form-control-sm" value="<?= htmlspecialchars($f_fecha_inicio) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Hasta</label>
                            <input type="date" name="fecha_fin" class="form-control form-control-sm" value="<?= htmlspecialchars($f_fecha_fin) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Conductor</label>
                            <select name="f_id_conductor" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                <?php foreach ($listaConductores as $c): ?>
                                    <option value="<?= $c['id_conductor'] ?>" <?= $f_id_conductor == $c['id_conductor'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['nombre_conductor']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Vehículo</label>
                            <select name="f_id_vehiculo" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                <?php foreach ($listaVehiculos as $v): ?>
                                    <option value="<?= $v['id_vehiculo'] ?>" <?= $f_id_vehiculo == $v['id_vehiculo'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($v['placa']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-sm btn-primary">🔍 Filtrar</button>
                            <a href="main.php?page=reportes" class="btn btn-sm btn-outline-secondary">Limpiar</a>
                            <a href="Controllers/ExportarReportesController.php?formato=excel&<?= $query_filtros ?>" class="btn btn-sm btn-success float-end ms-2">📊 Exportar Excel</a>
                            <a href="Controllers/ExportarReportesController.php?formato=pdf&<?= $query_filtros ?>" target="_blank" class="btn btn-sm btn-danger float-end">📄 Exportar PDF</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABLA DE REPORTES -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-bold">Historial de Reportes de Circulación</div>
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>H. Entrada</th>
                                <th>H. Salida</th>
                                <th>Conductor</th>
                                <th>Vehículo</th>
                                <th>Placa</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($listaReportes)): ?>
                                <?php foreach ($listaReportes as $row): ?>
                                <tr>
                                    <td><?= $row['id_reporte'] ?></td>
                                    <td><?= htmlspecialchars($row['fecha']) ?></td>
                                    <td><?= htmlspecialchars($row['hora_entrada']) ?></td>
                                    <td><?= htmlspecialchars($row['hora_salida']) ?></td>
                                    <td><?= htmlspecialchars($row['nombre_conductor']) ?></td>
                                    <td><?= htmlspecialchars($row['marca'] . ' ' . $row['modelo']) ?></td>
                                    <td><?= htmlspecialchars($row['placa']) ?></td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="main.php?page=reportes&editar=<?= $row['id_reporte'] ?>" class="btn btn-outline-dark" title="Editar">✏️</a>
                                            <a href="main.php?page=reportes&eliminar=<?= $row['id_reporte'] ?>" class="btn btn-outline-danger" onclick="return confirm('¿Eliminar este reporte?');" title="Eliminar">🗑️</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No hay reportes registrados para los filtros seleccionados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
