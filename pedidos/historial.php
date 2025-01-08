
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Pedidos</title>
    <link rel="shortcut icon" href="imagenes/logo.jpg" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <?php
    session_start();
    if(!isset($_SESSION['username'])) {
        header("Location:login.php");
        exit();
    }
    require_once __DIR__.'/../class/pedido.php';
    $pedido = new Pedido();
    $codClient = $_SESSION['codClient'];
    $usuarioUy = $_SESSION['usuarioUy'];
    $pedidos = $pedido->traerHistorial($codClient, $usuarioUy);
    
    // Contadores para KPIs
    $generalCount = 0;
    $accesoriosCount = 0;
    $outletCount = 0;
    $thirtyDaysAgo = new DateTime();
    $thirtyDaysAgo->modify('-30 days');

    foreach ($pedidos as $p) {
        if ($p['FECHA'] > $thirtyDaysAgo) {
            switch (strtoupper(trim($p['LEYENDA_1']))) {
                case 'PEDIDO GENERAL':
                    $generalCount += (int)$p['CANT'];
                    break;
                case 'PEDIDO ACCESORIOS':
                    $accesoriosCount += (int)$p['CANT'];
                    break;
                case 'PEDIDO OUTLET':
                    $outletCount += (int)$p['CANT'];
                    break;
            }
        }
    }

    // Procesamiento de datos para el gráfico
    function getLastDayOfWeek($date) {
        // Clonar la fecha para no modificar la original
        $lastDay = clone $date;
        
        // Obtener el número de día de la semana (1 = Monday, 7 = Sunday)
        $dayOfWeek = $lastDay->format('N');
        
        // Añadir días hasta llegar al domingo
        $daysToAdd = 7 - $dayOfWeek;
        $lastDay->modify("+{$daysToAdd} days");
        
        return $lastDay;
    }

    function getWeekNumber($date) {
        return $date->format("W");
    }

    function getYearWeek($date) {
        $lastDayOfWeek = getLastDayOfWeek($date);
        $year = $lastDayOfWeek->format("Y");
        $week = getWeekNumber($date);
        // Crear un valor numérico que permita ordenar correctamente por año y semana
        return intval($year) * 100 + intval($week);
    }

    $weeklyData = [];
    foreach ($pedidos as $pedido) {
        $fecha = $pedido['FECHA'];
        $lastDayOfWeek = getLastDayOfWeek($fecha);
        $yearWeek = getYearWeek($fecha);
        $tipo = strtoupper(trim($pedido['LEYENDA_1']));
        $cantidad = (int)$pedido['CANT'];

        if (!isset($weeklyData[$yearWeek])) {
            $weeklyData[$yearWeek] = [
                'semana' => "Sem " . getWeekNumber($fecha) . "/" . $lastDayOfWeek->format("Y"),
                'PEDIDO GENERAL' => 0,
                'PEDIDO ACCESORIOS' => 0,
                'PEDIDO OUTLET' => 0
            ];
        }
        
        if (isset($weeklyData[$yearWeek][$tipo])) {
            $weeklyData[$yearWeek][$tipo] += $cantidad;
        }
    }

    // Ordenar por año y semana
    ksort($weeklyData);

    $labels = [];
    $generalData = [];
    $accesoriosData = [];
    $outletData = [];

    foreach ($weeklyData as $data) {
        $labels[] = $data['semana'];
        $generalData[] = $data['PEDIDO GENERAL'];
        $accesoriosData[] = $data['PEDIDO ACCESORIOS'];
        $outletData[] = $data['PEDIDO OUTLET'];
    }
    ?>

    <div class="min-h-screen">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <a href="../index.php" class="flex items-center text-gray-600 hover:text-gray-900">
                            <i class="fas fa-home text-2xl"></i>
                            <span class="ml-2 font-medium">Inicio</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- KPIs -->
            <div class="mb-2 text-sm text-gray-500">
                Unidades solicitadas en los últimos 30 días
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <?php if ($generalCount > 0): ?>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <i class="fas fa-box text-white text-xl"></i>
                        </div>
                        <div class="ml-5">
                            <h3 class="text-lg font-medium text-gray-900">Pedido General</h3>
                            <div class="mt-1 text-3xl font-semibold text-gray-700"><?= $generalCount ?></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($accesoriosCount > 0): ?>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <i class="fas fa-puzzle-piece text-white text-xl"></i>
                        </div>
                        <div class="ml-5">
                            <h3 class="text-lg font-medium text-gray-900">Pedido Accesorios</h3>
                            <div class="mt-1 text-3xl font-semibold text-gray-700"><?= $accesoriosCount ?></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($outletCount > 0): ?>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <i class="fas fa-tag text-white text-xl"></i>
                        </div>
                        <div class="ml-5">
                            <h3 class="text-lg font-medium text-gray-900">Pedido Outlet</h3>
                            <div class="mt-1 text-3xl font-semibold text-gray-700"><?= $outletCount ?></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Gráfico de líneas -->
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Unidades Solicitadas por Semana</h3>
                <div style="height: 400px;">
                    <canvas id="weeklyOrdersChart"></canvas>
                </div>
            </div>

            <!-- Búsqueda -->
            <div class="mb-4">
                <div class="max-w-md">
                    <div class="relative">
                        <input type="text" 
                               id="searchInput"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Buscar pedidos...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nro Pedido</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cant Artículos</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($pedidos as $v): 
                                $fecha = $v['FECHA']->format('d/m/Y'); ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $fecha ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="detallePed.php?pedido=<?= $v['NRO_PEDIDO'] ?>&suc=<?= $codClient ?>&tipo=<?= $v['LEYENDA_1'] ?>" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <?= $v['NRO_PEDIDO'] ?>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $v['LEYENDA_1'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center"><?= (int)($v['CANT']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

    <script>
    // Búsqueda en tabla
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('tbody tr');

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const shouldShow = text.includes(searchTerm);
                row.style.display = shouldShow ? '' : 'none';
            });
        });

        // Inicializar gráfico
        const ctx = document.getElementById('weeklyOrdersChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [
                    {
                        label: 'Pedido General',
                        data: <?= json_encode($generalData) ?>,
                        borderColor: '#3B82F6',
                        backgroundColor: '#3B82F6',
                        tension: 0.1,
                        pointRadius: 4
                    },
                    {
                        label: 'Pedido Accesorios',
                        data: <?= json_encode($accesoriosData) ?>,
                        borderColor: '#8B5CF6',
                        backgroundColor: '#8B5CF6',
                        tension: 0.1,
                        pointRadius: 4
                    },
                    {
                        label: 'Pedido Outlet',
                        data: <?= json_encode($outletData) ?>,
                        borderColor: '#EAB308',
                        backgroundColor: '#EAB308',
                        tension: 0.1,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    });
    </script>
</body>
</html>