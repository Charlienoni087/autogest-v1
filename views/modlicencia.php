<?php
// CONFIGURACIÓN INICIAL E INCLUSIÓN DE CONEXIÓN

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Busqueda de archivo en la carpeta views '
if (!isset($conexion)) {
    include __DIR__ . '/../config/conexion.php';
}

// 1. LÓGICA PARA INSERTAR UNA LICENCIA
if (isset($_POST['guardar_licencia'])) {
    $numero_licencia = $_POST['numero_licencia'];
    $tipo_licencia   = $_POST['tipo_licencia'];
    
    // Como las categorías vienen en múltiples checkboxes, las unimos con comas
    $categorias_array = isset($_POST['categorias']) ? $_POST['categorias'] : [];
    $categorias_string = implode(", ", $categorias_array);
    
    $fecha_vencimiento = $_POST['fecha_vencimiento'];

    $sql = "INSERT INTO Licencia (numero_licencia, tipo_licencia, categorias, fecha_vencimiento) 
            VALUES ('$numero_licencia', '$tipo_licencia', '$categorias_string', '$fecha_vencimiento')";
    
    if ($conexion->query($sql) === TRUE) {
        echo "<div class='alert alert-success m-2'>¡Licencia registrada con éxito!</div>";
    } else {
        echo "<div class='alert alert-danger m-2'>Error: " . $conexion->error . "</div>";
    }
}

// 2. LÓGICA PARA LEER LAS LICENCIAS Y EXTRAER LOS CONTADORES (KPIs)
$resultado = $conexion->query("SELECT *, CURDATE() as hoy FROM Licencia");

// Contadores automáticos
$total_reg = $conexion->query("SELECT COUNT(*) as total FROM Licencia")->fetch_assoc()['total'];
$vencidas_reg = $conexion->query("SELECT COUNT(*) as total FROM Licencia WHERE fecha_vencimiento < CURDATE()")->fetch_assoc()['total'];
$por_vencer_reg = $conexion->query("SELECT COUNT(*) as total FROM Licencia WHERE fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AutoGest - Licencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-white shadow-sm border-0 text-center p-3">
                <h6 class="text-muted">Total Licencias</h6>
                <h3 class="text-primary fw-bold"><?= $total_reg ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-white shadow-sm border-0 text-center p-3">
                <h6 class="text-muted">Vencidas</h6>
                <h3 class="text-danger fw-bold"><?= $vencidas_reg ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-white shadow-sm border-0 text-center p-3">
                <h6 class="text-muted">Próximas a Vencer (30 días)</h6>
                <h3 class="text-warning fw-bold"><?= $por_vencer_reg ?></h3>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Licencias de Conducir</h4>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarLicencia">
                    Agregar Licencia
                </button>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAgregarLicencia">
                    Editar Licencia
                </button>
                <a href="usuarios.php" class="btn btn-secondary">← Volver a Usuarios</a>
            </div>
        </div>
    </div>

    <!-- Modal para agregar licencia -->
    <div class="modal fade" id="modalAgregarLicencia" tabindex="-1" aria-labelledby="modalAgregarLicenciaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalAgregarLicenciaLabel">Agregar nueva licencia</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form action="licencias.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Número de Licencia</label>
                            <input type="text" name="numero_licencia" class="form-control" required placeholder="ej. GTO-12345">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo de Licencia</label>
                            <select name="tipo_licencia" class="form-select" required>
                                <option value="Profesional">Ordinaria</option>
                                <option value="Estatal">Profesional</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Categorías</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="A"> <label class="form-check-label">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="B"> <label class="form-check-label">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="C"> <label class="form-check-label">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="C1"> <label class="form-check-label">4A</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="C2"> <label class="form-check-label">4B</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="C2"> <label class="form-check-label">5A</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="C2"> <label class="form-check-label">5B</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="C2"> <label class="form-check-label">6A</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="C2"> <label class="form-check-label">6B</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="C2"> <label class="form-check-label">7</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categorias[]" value="C2"> <label class="form-check-label">8</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha de Vencimiento</label>
                            <input type="date" name="fecha_vencimiento" class="form-control" required>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" name="guardar_licencia" class="btn btn-primary">Guardar Licencia</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-16">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Licencias de Conducir Registradas</div>
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>N° Licencia</th>
                                <th>Tipo</th>
                                <th>Categorías</th>
                                <th>Vencimiento</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            while($row = $resultado->fetch_assoc()): 
                                $fecha_venc = strtotime($row['fecha_vencimiento']);
                                $hoy = strtotime($row['hoy']);
                                $dias_restantes = ($fecha_venc - $hoy) / 86400;

                                // Lógica de colores de alerta para la Alcaldía
                                if ($dias_restantes < 0) {
                                    $clase_estado = "bg-danger";
                                    $texto_estado = "Vencida";
                                } elseif ($dias_restantes <= 30) {
                                    $clase_estado = "bg-warning text-dark";
                                    $texto_estado = "Por Vencer";
                                } else {
                                    $clase_estado = "bg-success";
                                    $texto_estado = "Vigente";
                                }
                            ?>
                            <tr>
                                <td><?= $row['id_licencia'] ?></td>
                                <td><?= htmlspecialchars($row['numero_licencia']) ?></td>
                                <td><?= htmlspecialchars($row['tipo_licencia']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($row['categorias']) ?></span></td>
                                <td class="<?= ($texto_estado != 'Vigente') ? 'text-danger fw-bold' : '' ?>">
                                    <?= $row['fecha_vencimiento'] ?>
                                </td>
                                <td><span class="badge <?= $clase_estado ?>"><?= $texto_estado ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>