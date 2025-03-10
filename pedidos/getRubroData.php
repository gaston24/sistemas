
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__.'/../class/pedido.php';

header('Content-Type: application/json');

try {
    if(!isset($_SESSION['username'])) {
        throw new Exception('No autorizado');
    }

    if(!isset($_GET['rubro'])) {
        throw new Exception('Falta el parámetro rubro');
    }

    $rubro = trim($_GET['rubro']);
    if (empty($rubro)) {
        throw new Exception('El rubro no puede estar vacío');
    }

    $pedido = new Pedido();
    $codClient = $_SESSION['codClient'];
    $usuarioUy = $_SESSION['usuarioUy'];
    $suc = isset($_SESSION['suc']) ? $_SESSION['suc'] : '53'; // valor por defecto si no existe

    // Debug log
    error_log("Procesando rubro: " . $rubro);
    error_log("codClient: " . $codClient);
    error_log("usuarioUy: " . $usuarioUy);
    error_log("suc: " . $suc);

    // Obtener datos
    $pedidosRubro = $pedido->traerHistorialRubro($codClient, $usuarioUy);
    $ventasRubro = $pedido->traerVentasRubro($codClient, $suc, $usuarioUy);

    // Debug log
    error_log("Pedidos obtenidos: " . count($pedidosRubro));
    error_log("Ventas obtenidas: " . count($ventasRubro));

    // Procesar datos
    $rubroData = [];

    foreach ($pedidosRubro as $p) {
        if (!isset($p['FECHA']) || !isset($p['RUBRO']) || !isset($p['CANT'])) {
            continue;
        }

        if (trim($p['RUBRO']) === $rubro) {
            $fecha = $p['FECHA'];
            if (!($fecha instanceof DateTime)) {
                continue;
            }

            $yearWeek = $fecha->format('Y-W');
            
            if (!isset($rubroData[$yearWeek])) {
                $rubroData[$yearWeek] = [
                    'semana' => "Sem " . $fecha->format("W") . "/" . $fecha->format("Y"),
                    'pedidos' => 0,
                    'ventas' => 0
                ];
            }
            
            $rubroData[$yearWeek]['pedidos'] += (float)$p['CANT'];
        }
    }

    foreach ($ventasRubro as $v) {
        if (!isset($v['FECHA']) || !isset($v['RUBRO']) || !isset($v['CANT_VENTAS'])) {
            continue;
        }

        if (trim($v['RUBRO']) === $rubro) {
            $fecha = $v['FECHA'];
            if (!($fecha instanceof DateTime)) {
                $fecha = new DateTime($fecha);
            }

            $yearWeek = $fecha->format('Y-W');
            
            if (!isset($rubroData[$yearWeek])) {
                $rubroData[$yearWeek] = [
                    'semana' => "Sem " . $fecha->format("W") . "/" . $fecha->format("Y"),
                    'pedidos' => 0,
                    'ventas' => 0
                ];
            }
            
            $rubroData[$yearWeek]['ventas'] += (float)$v['CANT_VENTAS'];
        }
    }

    // Ordenar por semana
    ksort($rubroData);

    // Preparar respuesta
    $response = [
        'labels' => [],
        'pedidosData' => [],
        'ventasData' => []
    ];

    foreach ($rubroData as $data) {
        $response['labels'][] = $data['semana'];
        $response['pedidosData'][] = $data['pedidos'];
        $response['ventasData'][] = $data['ventas'];
    }

    // Debug final
    error_log("Respuesta final: " . json_encode($response));

    echo json_encode($response);
    exit;

} catch (Exception $e) {
    error_log("Error en getRubroData.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'details' => 'Error procesando la solicitud'
    ]);
    exit;
}