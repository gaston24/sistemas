
<?php
header('Content-Type: application/json');

require_once "../Class/Articulo.php";

$accion = $_GET['accion'] ?? '';

try {
    $articulo = new Articulo();
    
    switch ($accion) {
        case 'traerArticulo':
            $codArticulo = $_POST['codArticulo'] ?? '';
            
            if (empty($codArticulo)) {
                echo json_encode([]);
                exit;
            }

            // Buscar el artículo por código
            $result = $articulo->buscarArticuloPorCodigo($codArticulo);
            
            // Sanitizar los resultados para garantizar consistencia
            if (!empty($result)) {
                foreach ($result as &$row) {
                    // Sanitizar campos numéricos
                    if (isset($row['PRECIO']) && $row['PRECIO'] === null) {
                        $row['PRECIO'] = 0;
                    }
                    
                    if (isset($row['PRECIO_S_IVA']) && $row['PRECIO_S_IVA'] === null) {
                        $row['PRECIO_S_IVA'] = 0;
                    }
                    
                    // Sanitizar campos de texto
                    $textFields = ['DESCRIPCION', 'DESTINO', 'TEMPORADA', 'RUBRO'];
                    foreach ($textFields as $field) {
                        if (isset($row[$field]) && $row[$field] === null) {
                            $row[$field] = '';
                        }
                    }
                    
                    // Manejar el campo de liquidación
                    if (isset($row['LIQUIDACION'])) {
                        // Normalizar el valor de liquidación
                        if ($row['LIQUIDACION'] === '1' || $row['LIQUIDACION'] === 1 || 
                            strtoupper($row['LIQUIDACION']) === 'SI' || strtoupper($row['LIQUIDACION']) === 'YES' || 
                            strtoupper($row['LIQUIDACION']) === 'TRUE') {
                            $row['LIQUIDACION'] = 'SI';
                        } else {
                            $row['LIQUIDACION'] = 'NO';
                        }
                    } else {
                        $row['LIQUIDACION'] = 'NO';
                    }
                }
            }
            
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