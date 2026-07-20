
<?php
// Datos de prueba 
$totalVehiculos = 5;
$conductoresActivos = 7;
$totalLicencias = 9;
$totalReportes = 2;
?>

<!-- Contenedor del Dashboard  -->
<div class="container-fluid px-4 py-3">
    <h2 class="mb-4" style="color: #0c3b2e;">
    Resumen Operativo
</h2>

    <!-- Fichas de Resumen (Cards) adaptadas a tus nuevos módulos -->
    <div class="row g-3 mb-4">
        <!-- Tarjeta 1: Vehículos -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card text-white h-100" style="background-color: #0c3d2e; border: none;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <h5 class="card-title text-center font-weight-bold mb-2">Total Vehículos</h5>
                    <p class="card-text fs-3 font-weight-bold m-0"><?php echo $totalVehiculos; ?></p>
                </div>
            </div>
        </div>
        <!-- Tarjeta 2: Conductores -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card text-white h-100" style="background-color:  #0c3d2e; border: none;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <h5 class="card-title text-center font-weight-bold mb-2">Conductores Activos</h5>
                    <p class="card-text fs-3 font-weight-bold m-0"><?php echo $conductoresActivos; ?></p>
                </div>
            </div>
        </div>
        <!-- Tarjeta 3: Licencias -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card text-white h-100" style="background-color: #0c3d2e; border: none;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <h5 class="card-title text-center font-weight-bold mb-2">Licencias Emitidas</h5>
                    <p class="card-text fs-3 font-weight-bold m-0"><?php echo $totalLicencias; ?></p>
                </div>
            </div>
        </div>
        <!-- Tarjeta 4: Reportes -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card text-white h-100" style="background-color: #0c3d2e; border: none;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <h5 class="card-title text-center font-weight-bold mb-2">Reportes Totales</h5>
                    <p class="card-text fs-3 font-weight-bold m-0"><?php echo $totalReportes; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Gráfico -->
    <div class="row mb-4">
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
    <div class="row g-4">
        <!-- Tabla de Últimos Movimientos -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm p-3 bg-white rounded h-100">
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
                                <!-- Datos de ejemplo dinámicos basados en tu tabla Reportes -->
                                <tr>
                                    <td>Marcos Gutiérrez</td>
                                    <td>10-02-2026</td>
                                    <td>08:00 AM</td>
                                    <td>05:00 PM</td>
                                </tr>
                                <tr>
                                    <td>Antonio Martínez</td>
                                    <td>11-02-2026</td>
                                    <td>07:30 AM</td>
                                    <td>04:30 PM</td>
                                </tr>
                                <tr>
                                    <td>Alejandro Zambrana</td>
                                    <td>12-02-2026</td>
                                    <td>09:00 AM</td>
                                    <td>06:30 PM</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas / Estado de Vehículos -->
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm p-3 bg-white rounded h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3" style="color: #0c3d2e; font-weight: bold;">Estatus de Vehículos</h5>
                    
                    <div class="p-2 mb-2 rounded" style="background-color: #ffba00;">
                        <span class="d-block font-weight-bold" style="color:  #0c3d2e; font-weight: bold;">Mantenimiento</span>
                        <small class="text-muted">Toyota Hilux - Placa M 2345</small>
                    </div>
                    <div class="p-2 mb-2 rounded" style="background-color: #6d9773; color: white;">
                        <span class="d-block font-weight-bold" style="font-weight: bold;">Activo en Ruta</span>
                        <small style="color: #e8f7f6;">Hyundai Accent - Placa L 8976</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Chart.js desde CDN para el gráfico interactivo -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficoReportes').getContext('2d');
    
    // Tus datos de Python migrados (Ejemplo: Reportes semanales por día)
    const dias = ["Lun", "Mar", "Mie", "Jue", "Vie", "Sab", "Dom"];
    const reportesPorDia = [12, 19, 8, 15, 22, 30, 10]; // Cantidad de salidas/reportes

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

