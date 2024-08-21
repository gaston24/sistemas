
<?php
header('Content-Type: application/json');

require_once "../Class/Articulo.php";

$accion = $_GET['accion'] ?? '';

try {
    $articulo = new Articulo();
    
    switch ($accion) {
        case 'traerArticulo':
            $codArticulo = $_POST['codArticulo'] ?? '';
            $usuarioUy = $_POST['usuarioUy'] ?? 0;
            
            // Asumiendo que necesitas crear un método específico para buscar un artículo por código
            $result = $articulo->buscarArticuloPorCodigo($codArticulo);
            
            echo json_encode($result);
            break;
        
        default:
            echo json_encode(['error' => 'Acción no reconocida']);
            break;
    }
} catch (Exception $e) {
    error_log("Error in ArticuloController: " . $e->getMessage());
    echo json_encode(['error' => 'Error interno del servidor']);
}