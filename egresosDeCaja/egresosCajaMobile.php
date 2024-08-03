
<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/sistemas/assets/js/js.php';
if(!isset($_SESSION['username'])) {
    header("Location:login.php");
    exit;
}

require_once 'class/Egreso.php';

$egreso = new Egreso();
$nroSucurs = $_SESSION['numsuc'];

$data = $egreso->traerGastosMob($nroSucurs);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga de Gastos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f0f2f5;
            padding: 20px;
        }
        .navbar {
            background-color: #3b5998;
            color: white;
        }
        .navbar-brand i {
            margin-right: 10px;
            font-size: 1.2em;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }
        .card-body {
            padding: 15px;
        }
        .card-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .card-subtitle {
            font-size: 14px;
            color: #65676b;
            margin-bottom: 5px;
        }
        .card-text {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .btn-group {
            width: 100%;
            flex-wrap: wrap;
        }
        .btn-group .btn {
            font-size: 12px;
            padding: 5px;
            margin-bottom: 5px;
        }
        .vertical-line {
            border-left: 3px solid #4267B2;
            padding-left: 10px;
            margin-left: -15px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <i class="bi bi-cash"></i>Carga de Gastos
            </span>
        </div>
    </nav>
    
    <div class="container" id="gastosList">
        <?php
        if (!is_array($data) || empty($data)) {
            echo "<p>No se encontraron gastos para mostrar.</p>";
        } else {
            foreach ($data as $gasto): 
        ?>
            <div class="card">
                <div class="card-body">
                    <div class="vertical-line">
                        <h5 class="card-title">
                            <?php 
                            echo htmlspecialchars($gasto['FECHA'] instanceof DateTime ? $gasto['FECHA']->format("Y-m-d") : $gasto['FECHA']); 
                            ?> - 
                            <?php echo htmlspecialchars($gasto['COD_COMP']); ?> 
                            <?php echo htmlspecialchars($gasto['N_COMP']); ?>
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            <?php echo htmlspecialchars($gasto['COD_CTA']); ?> 
                            <?php echo htmlspecialchars($gasto['DESC_CUENTA']); ?> 
                            <?php 
                            $monto = floatval($gasto['MONTO']);
                            if($monto < 0) {
                                $monto = abs($monto);
                                echo "- $" . number_format($monto, 0, '.', '.');
                            } else {
                                echo "$" . number_format($monto, 0, '.', '.');
                            }
                            ?>
                        </h6>
                        <p class="card-text"><?php echo htmlspecialchars($gasto['LEYENDA']); ?></p>
                        <div class="btn-group" role="group">
                            <?php if(!isset($gasto['guardado']) || $gasto['guardado'] != 1): ?>
                                <button type="button" class="btn btn-outline-primary" onclick="elegirImagen(this)">
                                    <i class="bi bi-camera"></i> Cámara
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-outline-secondary" onclick="mostrarImagen(this)">
                                <i class="bi bi-eye"></i> Ver
                            </button>
                            <?php if(!isset($gasto['guardado']) || $gasto['guardado'] != 1): ?>
                                <button type="button" class="btn btn-outline-danger" onclick="eliminarArchivo(this)">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-outline-success" onclick="guardarGasto(this)">
                                <i class="bi bi-save"></i> Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
            endforeach;
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/egresoCaja.js"></script>
    <script>
    function guardarGasto(button) {
        // Aquí puedes agregar la lógica para guardar el gasto
        alert('Guardando gasto...');
        // Ejemplo: Puedes obtener los datos del gasto desde los elementos padres del botón
        var card = button.closest('.card');
        var gastoId = card.dataset.gastoId; // Asumiendo que has agregado un data-gasto-id a cada card
        // Realiza una llamada AJAX para guardar el gasto
        // ...
    }
    </script>
</body>
</html>