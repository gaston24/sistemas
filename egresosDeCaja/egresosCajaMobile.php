
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Carga de Gastos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f0f2f5;
            padding: 10px;
        }
        .navbar {
            background-color: #3b5998;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-brand i {
            margin-right: 10px;
            font-size: 1em;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }
        .card-body {
            padding: 5px;
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
            justify-content: space-between;
        }
        .btn-group .btn {
            font-size: 12px;
            padding: 8px;
            margin-bottom: 5px;
            flex-grow: 1;
            max-width: calc(50% - 5px);
        }
        .vertical-line {
            border-left: 3px solid #4267B2;
            padding-left: 10px;
            margin-left: -15px;
        }
        #nroSucursal {
            display: none;
        }
        .search-container {
            padding: 10px;
            background-color: #f8f9fa;
            position: sticky;
            top: 56px; /* Ajusta este valor según la altura de tu navbar */
            z-index: 999;
        }
        .search-wrapper {
            position: relative;
        }
        #searchInput {
            width: 100%;
            padding: 8px 8px 8px 35px; /* Aumentamos el padding izquierdo para dar espacio al icono */
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .clear-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            cursor: pointer;
            display: none; /* Inicialmente oculto */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark mb-2">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
            <i class="bi bi-camera-fill"></i>Carga de Fotos | Gastos
            </span>
        </div>
    </nav>
    
    <div class="search-container">
        <div class="search-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchInput" placeholder="Buscar por comprobante...">
            <i class="bi bi-x-circle clear-icon" id="clearSearch"></i>
        </div>
    </div>

    <div id="nroSucursal"><?php echo $nroSucurs; ?></div>
    
    <div class="container" id="gastosList">
        <?php
        if (!is_array($data) || empty($data)) {
            echo "<p>No se encontraron gastos para mostrar.</p>";
        } else {
            foreach ($data as $gasto): 
        ?>
            <div class="card" data-ncomp="<?php echo htmlspecialchars($gasto['N_COMP']); ?>">
                <div class="card-body">
                    <div class="vertical-line">
                        <h5 class="card-title">
                            <?php 
                            echo htmlspecialchars($gasto['FECHA'] instanceof DateTime ? $gasto['FECHA']->format("Y-m-d") : $gasto['FECHA']); 
                            ?> | 
                            <?php echo htmlspecialchars($gasto['COD_COMP']); ?> 
                            <?php echo htmlspecialchars($gasto['N_COMP']); ?>
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            <?php echo htmlspecialchars($gasto['COD_CTA']); ?> | 
                            <?php echo htmlspecialchars($gasto['DESC_CUENTA']); ?> | 
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

    <input type="file" id="archivos" style="display: none;" accept="image/*" capture="camera">

    <div id="carruselImagenes" class="modal fade" tabindex="-1" aria-hidden="true">
        <!-- El contenido del carrusel se generará dinámicamente con JavaScript -->
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/egresoCajaMobile.js"></script>

    <script>

        // Función para filtrar las tarjetas basándose en la búsqueda
        function filterCards() {
            var input, filter, cards, card, ncomp, i;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            cards = document.getElementsByClassName('card');

            for (i = 0; i < cards.length; i++) {
                card = cards[i];
                ncomp = card.getAttribute('data-ncomp');
                if (ncomp.toUpperCase().indexOf(filter) > -1) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            }

            // Mostrar u ocultar el ícono de limpieza
            document.getElementById('clearSearch').style.display = input.value.length > 0 ? "block" : "none";
        }

        // Función para limpiar el campo de búsqueda
        function clearSearch() {
            var input = document.getElementById('searchInput');
            input.value = '';
            filterCards(); // Esto mostrará todas las tarjetas de nuevo
        }

        // Agregar event listeners
        document.addEventListener('DOMContentLoaded', function() {
            var searchInput = document.getElementById('searchInput');
            var clearButton = document.getElementById('clearSearch');

            if (searchInput) {
                searchInput.addEventListener('keyup', filterCards);
                searchInput.addEventListener('input', filterCards); // Para manejar el caso de cortar/pegar
            }

            if (clearButton) {
                clearButton.addEventListener('click', clearSearch);
            }
        });

    </script>

</body>
</html>