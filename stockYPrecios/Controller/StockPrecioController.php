<?php
header('Content-Type: application/json');

require_once "../class/StockPrecio.php";

$accion = $_GET['accion'] ?? '';

try {
    $stockPrecio = new StockPrecio();
    
    switch ($accion) {
        case 'traerArticulos':
            $codArticulo = $_POST['codArticulo'] ?? '';
            $usuarioUy = $_POST['usuarioUy'] ?? 0;
            $result = $stockPrecio->traerMaestroArticulo($codArticulo, $usuarioUy);
            echo json_encode($result);
            break;
        
        case 'traerVariantes':
            $codArticulo = $_POST['codArticulo'] ?? '';
            $usuarioUy = $_POST['usuarioUy'] ?? 0;
            $result = $stockPrecio->traerVariantes($codArticulo, $usuarioUy);
            echo json_encode($result);
            break;
        
        default:
            echo json_encode(['error' => 'Acción no reconocida']);
            break;
    }
} catch (Exception $e) {
    error_log("Error in StockPrecioController: " . $e->getMessage());
    echo json_encode(['error' => 'Error interno del servidor']);
}