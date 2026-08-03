<?php
require_once __DIR__ . '/../Config/conexion.php';

/**
 * Ejecuta un COUNT(*) simple y devuelve el total como entero.
 * Evita repetir el mismo patrón query() -> fetch_assoc() -> ['total'] varias veces.
 */
function contarRegistros(mysqli $conexion, string $sql): int
{
    $resultado = $conexion->query($sql);
    return $resultado ? (int) $resultado->fetch_assoc()['total'] : 0;
}

// --- Tarjetas de resumen ---
$totalVehiculos     = contarRegistros($conexion, "SELECT COUNT(*) AS total FROM Vehiculos");
$conductoresActivos = contarRegistros($conexion, "SELECT COUNT(*) AS total FROM Conductores WHERE estado = 1");
$totalLicencias     = contarRegistros($conexion, "SELECT COUNT(*) AS total FROM Licencia");
$totalReportes      = contarRegistros($conexion, "SELECT COUNT(*) AS total FROM Reportes");

// --- Últimos 8 reportes de circulación ---
$listaReportes = [];
$stmtReportes = $conexion->prepare(
    "SELECT r.fecha, r.hora_entrada, r.hora_salida, c.nombre_conductor, v.marca, v.modelo, ci.placa
     FROM reportes r
     INNER JOIN conductores c ON r.id_conductor = c.id_conductor
     INNER JOIN vehiculos v ON r.id_vehiculo = v.id_vehiculo
     LEFT JOIN circulacion ci ON v.id_circulacion = ci.id_circulacion
     ORDER BY r.fecha DESC, r.hora_entrada DESC
     LIMIT 8"
);
if ($stmtReportes) {
    $stmtReportes->execute();
    $resultReportes = $stmtReportes->get_result();
    $listaReportes = $resultReportes ? $resultReportes->fetch_all(MYSQLI_ASSOC) : [];
    $stmtReportes->close();
}

// --- Estatus de vehículos ---

/**
 * Decide cómo mostrar un vehículo (etiqueta y colores) según su estado.
 * Separa la lógica de "qué significa este estado" de cómo se dibuja en el HTML.
 */
function clasificarEstadoVehiculo(?string $estado): array
{
    $estado = trim($estado ?? '');
    $esCritico = stripos($estado, 'mantenimiento') !== false
        || stripos($estado, 'repar') !== false
        || stripos($estado, 'crit') !== false;

    if ($esCritico) {
        return [
            'label'        => 'Mantenimiento',
            'bgColor'      => '#ffba00',
            'textColor'    => '#0c3d2e',
            'subTextColor' => '#4a3b00',
        ];
    }

    return [
        'label'        => 'Activo en Ruta',
        'bgColor'      => '#6d9773',
        'textColor'    => 'white',
        'subTextColor' => '#e8f7f6',
    ];
}

$estadoVehiculos = [];
$stmtEstados = $conexion->prepare(
    "SELECT v.id_vehiculo, v.marca, v.modelo, ci.placa, v.estado
     FROM vehiculos v
     LEFT JOIN circulacion ci ON v.id_circulacion = ci.id_circulacion
     ORDER BY CASE WHEN LOWER(v.estado) LIKE '%mantenimiento%' THEN 0 ELSE 1 END, v.marca, v.modelo"
);
if ($stmtEstados) {
    $stmtEstados->execute();
    $resultEstados = $stmtEstados->get_result();
    $estadoVehiculos = $resultEstados ? $resultEstados->fetch_all(MYSQLI_ASSOC) : [];
    $stmtEstados->close();
}

// Generar los últimos 7 días como claves del arreglo, iniciando en 0 reportes
$reportesPorDia = [];
for ($i = 6; $i >= 0; $i--) {
    $fecha = date('Y-m-d', strtotime("-$i days"));
    $reportesPorDia[$fecha] = 0;
}

$fechaInicio = array_key_first($reportesPorDia); // fecha más antigua (hace 6 días)
$fechaFin    = array_key_last($reportesPorDia);  // fecha más reciente (hoy)

// Consultar cuántos reportes hay por día dentro de ese rango
$stmtChart = $conexion->prepare(
    "SELECT DATE(fecha) AS fecha, COUNT(*) AS total
     FROM reportes
     WHERE fecha BETWEEN ? AND ?
     GROUP BY DATE(fecha)"
);

if ($stmtChart) {
    $stmtChart->bind_param('ss', $fechaInicio, $fechaFin);
    $stmtChart->execute();
    $resultChart = $stmtChart->get_result();

    // Rellenar el arreglo con los totales reales que sí tienen reportes
    while ($fila = $resultChart->fetch_assoc()) {
        $fechaFila = date('Y-m-d', strtotime($fila['fecha']));
        if (isset($reportesPorDia[$fechaFila])) {
            $reportesPorDia[$fechaFila] = (int) $fila['total'];
        }
    }
    $stmtChart->close();
}

$chartLabels = [];
$chartData = [];
foreach ($reportesPorDia as $fecha => $total) {
    $chartLabels[] = date('d/m', strtotime($fecha)); // ej: "28/07"
    $chartData[]    = $total;
}
?>

<!-- Contenedor del Dashboard  -->
<div class="container-fluid px-4 py-3">
    <h2 class="mb-4" style="color: #0c3b2e;">
    Resumen Operativo
</h2>

    <!-- Fichas de Resumen (Cards) adaptadas a tus nuevos módulos -->
    <div class="row g-3 mb-4">
        <!-- Tarjeta 1: Vehículos -->
        <div class="col-12 col-sm-6 col-xl-3" id="tarjeta1">
            <div class="card text-white h-100" style="background-color: #0c3d2e; border: none;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <h5 class="card-title text-center font-weight-bold mb-2">Total Vehículos</h5>
                    <p class="card-text fs-3 font-weight-bold m-0"><?php echo $totalVehiculos; ?></p>
                </div>
            </div>
        </div>
        <!-- Tarjeta 2: Conductores -->
        <div class="col-12 col-sm-6 col-xl-3" id="tarjeta2">
            <div class="card text-white h-100" style="background-color:  #0c3d2e; border: none;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <h5 class="card-title text-center font-weight-bold mb-2">Conductores Activos</h5>
                    <p class="card-text fs-3 font-weight-bold m-0"><?php echo $conductoresActivos; ?></p>
                </div>
            </div>
        </div>
        <!-- Tarjeta 3: Licencias -->
        <div class="col-12 col-sm-6 col-xl-3" id="tarjeta3">
            <div class="card text-white h-100" style="background-color: #0c3d2e; border: none;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <h5 class="card-title text-center font-weight-bold mb-2">Licencias Emitidas</h5>
                    <p class="card-text fs-3 font-weight-bold m-0"><?php echo $totalLicencias; ?></p>
                </div>
            </div>
        </div>
        <!-- Tarjeta 4: Reportes -->
        <div class="col-12 col-sm-6 col-xl-3" id="tarjeta4">
            <div class="card text-white h-100" style="background-color: #0c3d2e; border: none;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <h5 class="card-title text-center font-weight-bold mb-2">Reportes Totales</h5>
                    <p class="card-text fs-3 font-weight-bold m-0"><?php echo $totalReportes; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Gráfico -->
    <div class="row mb-4" id="grafico">
        <div class="col-12">
            <div class="card shadow-sm p-3 bg-white rounded">
                <div class="card-body">
                    <h5 class="card-title mb-3 text-center" style="color:  #0c3d2e;">Frecuencia de Salidas Semanales</h5>
                    <div style="position: relative; height:250px; width:100%">
                        <canvas id="graficoReportes"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección Inferior: Historial Reciente de Reportes / Vehículos en estado Crítico -->
    <div class="row g-4" id="movimiento">
        <!-- Tabla de Últimos Movimientos -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm p-3 bg-white rounded h-100" >
                <div class="card-body">
                    <h5 class="card-title mb-3" style="color: #ffba00; font-weight: bold;">Últimos Reportes de Circulación</h5>
                    <div class="table-responsive">
                        <table class="table align-middle table-hover" style="background-color: #E0F7FA;">
                            <thead style="background-color: #ffba00; color: #003598;">
                                <tr>
                                    <th scope="col">Conductor</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">H. Entrada</th>
                                    <th scope="col">H. Salida</th>
                                </tr>
                            </thead>
                            <tbody style="color: #012939;">
                                <?php if (!empty($listaReportes)): ?>
                                    <?php foreach ($listaReportes as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['nombre_conductor'] ?? 'Sin conductor') ?></td>
                                            <td><?= htmlspecialchars($row['fecha'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($row['hora_entrada'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($row['hora_salida'] ?? '-') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No hay reportes registrados.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas / Estado de Vehículos -->
        <div class="col-12 col-lg-4" id="estado">
            <div class="card shadow-sm p-3 bg-white rounded h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3" style="color: #0c3d2e; font-weight: bold;">Estatus de Vehículos</h5>

                    <?php if (!empty($estadoVehiculos)): ?>
                        <?php foreach ($estadoVehiculos as $vehiculo): ?>
                            <?php $estilo = clasificarEstadoVehiculo($vehiculo['estado'] ?? ''); ?>
                            <div class="p-2 mb-2 rounded" style="background-color: <?= $estilo['bgColor'] ?>; color: <?= $estilo['textColor'] ?>;">
                                <span class="d-block fw-bold"><?= htmlspecialchars($estilo['label']) ?></span>
                                <small style="color: <?= $estilo['subTextColor'] ?>;">
                                    <?= htmlspecialchars(($vehiculo['marca'] ?? 'Vehículo') . ' ' . ($vehiculo['modelo'] ?? '') . ' - Placa ' . ($vehiculo['placa'] ?? 'Sin placa')) ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-muted">No hay vehículos registrados.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Chart.js desde CDN para el gráfico interactivo -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficoReportes').getContext('2d');
    const dias = <?= json_encode($chartLabels) ?>;
    const reportesPorDia = <?= json_encode($chartData) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dias,
            datasets: [{
                label: 'Cantidad de Viajes',
                data: reportesPorDia,
                backgroundColor: '#0c3d2e',
                borderWidth: 0,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f0f0f0' },
                    ticks: { color: '#012939' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#012939' }
                }
            }
        }
    });
</script>