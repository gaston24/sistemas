<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Remito</title>
    <link rel="shortcut icon" href="XL.png" />
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
    $suc = $_GET['suc'];
    $nroPedido = $_GET['pedido'];
    $tipo = $_GET['tipo'];
    $usuarioUy = $_SESSION['usuarioUy'];
    $pedido = new Pedido();
    $pedidos = $pedido->traerDetallePedido($nroPedido, $suc, $usuarioUy);

    $totalItems = 0;
    foreach ($pedidos as $v) {
        $totalItems += (int)$v['CANT'];
    }
    ?>

    <div class="min-h-screen">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="historial.php" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </a>
                        <h1 class="ml-4 text-xl font-semibold text-gray-900">
                            Pedido: <?= htmlspecialchars($nroPedido) ?> - <?= htmlspecialchars($tipo) ?>
                        </h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button onclick="exportToExcel()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-file-excel mr-2"></i>
                            Excel
                        </button>
                        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <i class="fas fa-print mr-2"></i>
                            Imprimir
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-500">Total de Artículos</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900"><?= $totalItems ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Tipo de Pedido</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900"><?= htmlspecialchars($tipo) ?></div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="detalleTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($pedidos as $v):
                                $fecha = $v['FECHA']->format('d/m/Y'); ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($fecha) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($v['COD_ARTICU']) ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($v['DESCRIPCIO']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= (int)$v['CANT'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

    <script>
    function exportToExcel() {
        const table = document.getElementById('detalleTable');
        const ws = XLSX.utils.table_to_sheet(table);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Detalle Pedido');
        
        // Agregar información del pedido
        const fileName = `Pedido_${<?= json_encode($nroPedido) ?>}_${<?= json_encode($tipo) ?>}.xlsx`;
        XLSX.writeFile(wb, fileName);
    }

    // Estilos para impresión
    const style = document.createElement('style');
    style.textContent = `
        @media print {
            body { 
                background: white;
                padding: 0;
                margin: 0;
            }
            nav, .shadow { 
                box-shadow: none !important;
            }
            nav button, nav a, nav i { 
                display: none !important;
            }
            nav h1 {
                display: none !important;
            }
            main {
                padding: 0 !important;
            }
            .bg-white {
                background: white !important;
                box-shadow: none !important;
                border: none !important;
            }
            table {
                width: 100% !important;
            }
            @page { 
                margin: 1cm;
                size: portrait;
            }
        }
    `;
    document.head.appendChild(style);
    </script>
</body>
</html>