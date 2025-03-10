
<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location:login.php");
    exit();
}
require_once __DIR__.'/../class/pedido.php';

// Inicialización
$pedido = new Pedido();
$codClient = $_SESSION['codClient'];
$usuarioUy = $_SESSION['usuarioUy'];
$suc = $_SESSION['numsuc'];

// Obtener datos básicos
$pedidos = $pedido->traerHistorial($codClient, $usuarioUy);
$rubrosDisponibles = $pedido->traerRubros($codClient, $usuarioUy);
$defaultRubro = !empty($rubrosDisponibles) ? $rubrosDisponibles[0] : '';

// Obtener datos específicos
$pedidosRubro = $pedido->traerHistorialRubro($codClient, $usuarioUy);
$ventasRubro = $pedido->traerVentasRubro($codClient, $suc, $usuarioUy);

// Funciones auxiliares
function getLastDayOfWeek($date) {
    $lastDay = clone $date;
    $dayOfWeek = $lastDay->format('N');
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
    return intval($year) * 100 + intval($week);
}

// Procesar datos para KPIs
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

// Procesar datos para el gráfico de pedidos
$weeklyData = [];
foreach ($pedidos as $p) {
    $fecha = $p['FECHA'];
    $lastDayOfWeek = getLastDayOfWeek($fecha);
    $yearWeek = getYearWeek($fecha);
    $tipo = strtoupper(trim($p['LEYENDA_1']));
    $cantidad = (int)$p['CANT'];

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

// Ordenar y preparar datos para el gráfico de pedidos
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

// Procesar datos para el gráfico de rubros
$rubroData = [];

if (!empty($defaultRubro)) {
    foreach ($pedidosRubro as $p) {
        if (trim($p['RUBRO']) === $defaultRubro) {
            $fecha = $p['FECHA'];
            $lastDayOfWeek = getLastDayOfWeek($fecha);
            $yearWeek = getYearWeek($fecha);
            $cantidad = (float)$p['CANT'];

            if (!isset($rubroData[$yearWeek])) {
                $rubroData[$yearWeek] = [
                    'semana' => "Sem " . getWeekNumber($fecha) . "/" . $lastDayOfWeek->format("Y"),
                    'pedidos' => 0,
                    'ventas' => 0
                ];
            }
            
            $rubroData[$yearWeek]['pedidos'] += $cantidad;
        }
    }

    foreach ($ventasRubro as $v) {
        if (trim($v['RUBRO']) === $defaultRubro) {
            $fecha = $v['FECHA'];
            $yearWeek = getYearWeek($fecha);
            $cantidad = (float)$v['CANT_VENTAS'];
            
            if (!isset($rubroData[$yearWeek])) {
                $rubroData[$yearWeek] = [
                    'semana' => "Sem " . getWeekNumber($fecha) . "/" . getLastDayOfWeek($fecha)->format("Y"),
                    'pedidos' => 0,
                    'ventas' => 0
                ];
            }
            
            $rubroData[$yearWeek]['ventas'] += $cantidad;
        }
    }
}

// Ordenar por semana y preparar datos para el gráfico de rubros
ksort($rubroData);
$rubroLabels = [];
$rubroPedidosData = [];
$rubroVentasData = [];

foreach ($rubroData as $data) {
    $rubroLabels[] = $data['semana'];
    $rubroPedidosData[] = $data['pedidos'];
    $rubroVentasData[] = $data['ventas'];
}

// Preparar datos para JavaScript
$chartData = [
    'labels' => $labels,
    'generalData' => $generalData,
    'accesoriosData' => $accesoriosData,
    'outletData' => $outletData,
    'rubroLabels' => $rubroLabels,
    'rubroPedidosData' => $rubroPedidosData,
    'rubroVentasData' => $rubroVentasData,
    'defaultRubro' => $defaultRubro,
    'suc' => $suc
];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Pedidos</title>
    <link rel="shortcut icon" href="../../css/icono.jpg" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="style/historial.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navegación -->
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
                <div class="bg-white rounded-lg shadow p-6 kpi-card">
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
                <div class="bg-white rounded-lg shadow p-6 kpi-card">
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
                <div class="bg-white rounded-lg shadow p-6 kpi-card">
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

            <!-- Contenedor de gráficos -->
            <div class="charts-container">
                <!-- Solapas -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px" aria-label="Tabs">
                            <button onclick="switchTab('pedidos')" 
                                    class="tab-btn active-tab w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm"
                                    id="tab-pedidos">
                                Unidades Solicitadas por Semana
                            </button>
                            <button onclick="switchTab('rubros')" 
                                    class="tab-btn w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm"
                                    id="tab-rubros">
                                Unidades Solicitadas x Rubro
                            </button>
                        </nav>
                    </div>
                    
                    <div class="p-4">
                        <!-- Gráfico de pedidos -->
                        <div id="chart-pedidos" class="chart-container">
                            <div style="height: 400px;">
                                <canvas id="weeklyOrdersChart"></canvas>
                            </div>
                        </div>
                        
                        <!-- Gráfico de rubros -->
                        <div id="chart-rubros" class="chart-container" style="display: none;">
                            <!-- Selector de rubros -->
                            <div class="form-select-container">
                                <label for="rubroSelect" class="block text-sm font-medium text-gray-700 mb-2">
                                    Seleccionar Rubro a Visualizar
                                </label>
                                <select id="rubroSelect" class="form-select block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Seleccione un rubro...</option>
                                    <?php foreach ($rubrosDisponibles as $rubro): ?>
                                        <option value="<?= htmlspecialchars($rubro) ?>">
                                            <?= htmlspecialchars($rubro) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div style="height: 400px;">
                                <canvas id="weeklyRubrosChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de tabla -->
            <div class="table-section">
                <!-- Búsqueda -->
                <div class="mb-4">
                    <div class="max-w-md">
                        <div class="relative">
                            <input type="text" 
                                   id="searchInput"
                                   class="search-input w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Buscar pedidos...">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="table-container overflow-x-auto">
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
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        // Pasar los datos PHP a JavaScript
        const chartData = <?= json_encode($chartData) ?>;
    </script>
    <script src="js/historial.js"></script>
</body>
</html>