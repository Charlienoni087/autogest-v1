<?php

require_once __DIR__ . '/../Config/conexion.php';
require_once __DIR__ . '/../Models/reportes.php';

$reportesModel = new Reportes($conexion);

$fecha_inicio = $_REQUEST['fecha_inicio'] ?? '';
$fecha_fin = $_REQUEST['fecha_fin'] ?? '';
$id_conductor = $_REQUEST['f_id_conductor'] ?? '';
$id_vehiculo = $_REQUEST['f_id_vehiculo'] ?? '';
$formato = $_REQUEST['formato'] ?? 'excel';

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
        :root {
            --azul-oscuro: #0c3b2e;
            --azul-primario: #0c3b2e;
            --azul-acento: #0c3b2e;
            --celeste: #d1f3f7;
            --gris-texto: #64748b;
            --borde: #cbd5e1;
        }

        * { box-sizing: border-box; }

        @page {
            size: A4;
            margin: 15mm 12mm;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: var(--azul-oscuro);
            margin: 0;
            padding: 0 40px;
            background: #ffffff;
        }

        /* ---- BARRA DE ACCIONES ---- */
        .toolbar {
            position: sticky;
            top: 0;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 18px 0;
            background: #ffffff;
            z-index: 10;
        }

        .btn-imprimir {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: var(--azul-acento);
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(12,59,46,0.25);
            transition: background-color .15s ease, transform .15s ease;
        }

        .btn-imprimir:hover {
            background-color: #0a2f24;
            transform: translateY(-1px);
        }

        /* ---- ENCABEZADO CON LOGO ---- */
        .encabezado {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid var(--azul-acento);
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .encabezado-izquierda {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .encabezado-izquierda img {
            height: 60px;
            width: auto;
            object-fit: contain;
        }

        .encabezado-titulos h2 {
            color: var(--azul-primario);
            margin: 0;
            font-size: 22px;
            letter-spacing: 0.3px;
        }

        .encabezado-titulos p.subtitulo {
            color: var(--gris-texto);
            margin: 2px 0 0;
            font-size: 13px;
        }

        .encabezado-derecha {
            text-align: right;
            font-size: 12px;
            color: var(--gris-texto);
        }

        .encabezado-derecha strong {
            display: block;
            color: var(--azul-oscuro);
            font-size: 13px;
        }

        /* ---- TABLA ---- */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        th, td {
            border: 1px solid var(--borde);
            padding: 9px 10px;
            font-size: 13px;
            text-align: left;
        }

        th {
            background-color: var(--celeste);
            color: var(--azul-primario);
            text-transform: uppercase;
            font-size: 11.5px;
            letter-spacing: 0.4px;
        }

        tr:nth-child(even) { background-color: #f4f9fb; }
        tr:hover { background-color: #eaf6f8; }

        /* ---- PIE DE PÁGINA ---- */
        .pie-pagina {
            margin-top: 30px;
            padding-top: 10px;
            padding-bottom: 30px;
            border-top: 1px solid var(--borde);
            font-size: 11px;
            color: var(--gris-texto);
            text-align: center;
        }

        .no-imprimir {
            display: block;
        }

        @media print {
            .no-imprimir { display: none !important; }
            body { padding: 0; }
            table { box-shadow: none; }
            .encabezado { margin-top: 0; }
        }
    </style>
</head>
<body>
    <div class="toolbar no-imprimir">
        <button class="btn-imprimir" onclick="window.print()">
            🖨️ Guardar como PDF / Imprimir
        </button>
    </div>

    <div class="encabezado">
        <div class="encabezado-izquierda">
            <img src="../assets/autogest-logo.png" alt="Logo AutoGest">
            <div class="encabezado-titulos">
                <h2>AutoGest</h2>
                <p class="subtitulo">Control de entradas y salidas de vehículos</p>
            </div>
        </div>
        <div class="encabezado-derecha">
            <strong>Generado el <?= date('d-m-Y') ?></strong>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th><th>H. Entrada</th><th>H. Salida</th>
                <th>Conductor</th><th>Vehículo</th><th>Placa</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reportes)): ?>
                <tr><td colspan="6" style="text-align:center;">No hay reportes para los filtros seleccionados.</td></tr>
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

    <div class="pie-pagina">
        Reporte generado automáticamente por el sistema AutoGest
    </div>
</body>
</html>