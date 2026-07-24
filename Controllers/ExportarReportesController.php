<?php

require_once __DIR__ . '/../Config/conexion.php';
require_once __DIR__ . '/../Models/reportes.php';

$reportesModel = new Reportes($conexion);

$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';
$id_conductor = $_GET['f_id_conductor'] ?? '';
$id_vehiculo = $_GET['f_id_vehiculo'] ?? '';
$formato = $_GET['formato'] ?? 'excel';

$hay_filtros = !empty($fecha_inicio) || !empty($fecha_fin) || !empty($id_conductor) || !empty($id_vehiculo);

if ($hay_filtros) {
    $reportes = $reportesModel->obtenerFiltrados(
        $fecha_inicio ?: null,
        $fecha_fin ?: null,
        $id_conductor !== '' ? intval($id_conductor) : null,
        $id_vehiculo !== '' ? intval($id_vehiculo) : null
    );
} else {
    $reportes = $reportesModel->obtenerTodos();
}


// EXPORTAR A EXCEL 

if ($formato === 'excel') {
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=reportes_" . date('Ymd_His') . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "\xEF\xBB\xBF"; //bom para UTF-8
    echo "<table border='1'>";
    echo "<tr>
            <th>Fecha</th><th>Hora Entrada</th><th>Hora Salida</th>
            <th>Conductor</th><th>Vehículo</th><th>Placa</th>
          </tr>";

    foreach ($reportes as $r) {
        echo "<tr>";
       
        echo "<td>" . htmlspecialchars($r['fecha']) . "</td>";
        echo "<td>" . htmlspecialchars($r['hora_entrada']) . "</td>";
        echo "<td>" . htmlspecialchars($r['hora_salida']) . "</td>";
        echo "<td>" . htmlspecialchars($r['nombre_conductor']) . "</td>";
        echo "<td>" . htmlspecialchars($r['marca'] . ' ' . $r['modelo']) . "</td>";
        echo "<td>" . htmlspecialchars($r['placa']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    exit();
}


// EXPORTAR A PDF 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Circulación - AutoGest</title>
    <style>
        body { font-family: Arial, sans-serif; color: #012939; margin: 30px; }
        h2 { color: #1e09df; margin-bottom: 0; }
        p.subtitulo { color: #64748b; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; font-size: 13px; text-align: left; }
        th { background-color: #d1f3f7; color: #003598; }
        tr:nth-child(even) { background-color: #f4f9fb; }
        .no-imprimir { margin-bottom: 15px; }
        @media print {
            .no-imprimir { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-imprimir">
        <button onclick="window.print()">🖨️ Guardar como PDF / Imprimir</button>
    </div>

    <h2>AutoGest — Reporte de Circulación</h2>
    <p class="subtitulo">Generado el <?= date('d-m-Y H:i') ?></p>

    <table>
        <thead>
            <tr>
                <th>Fecha</th><th>H. Entrada</th><th>H. Salida</th>
                <th>Conductor</th><th>Vehículo</th><th>Placa</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reportes)): ?>
                <tr><td colspan="7" style="text-align:center;">No hay reportes para los filtros seleccionados.</td></tr>
            <?php else: ?>
                <?php foreach ($reportes as $r): ?>
                    <tr>
                        
                        <td><?= htmlspecialchars($r['fecha']) ?></td>
                        <td><?= htmlspecialchars($r['hora_entrada']) ?></td>
                        <td><?= htmlspecialchars($r['hora_salida']) ?></td>
                        <td><?= htmlspecialchars($r['nombre_conductor']) ?></td>
                        <td><?= htmlspecialchars($r['marca'] . ' ' . $r['modelo']) ?></td>
                        <td><?= htmlspecialchars($r['placa']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <script>

        window.onload = () => window.print();
    </script>
</body>
</html>
