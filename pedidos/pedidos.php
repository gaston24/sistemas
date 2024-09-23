<?php
session_start();

if (!isset($_SESSION['username'])) {

	header("Location:../../login.php");
} elseif ($_SESSION['habPedidos'] == 0 && $_SESSION['numsuc'] > 100) {

	header("Location:../index.php");
} else {
	include_once __DIR__.'/../class/pedido.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/sistemas/assets/js/js.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/sistemas/Controlador/cargaPedidoNew.php';
	// CONSULTAS EL DEPO
	$_SESSION['depo'] = '01';

	$suc = $_SESSION['numsuc'];
	$codClient = $_SESSION['username'];
	$tipo_cli = $_SESSION['tipo'];
	$esOutlet = $_SESSION['esOutlet'];
	$esUsuarioUy = $_SESSION['usuarioUy'];
	$db = 'central';

	if($esUsuarioUy == 1){

		$db = 'uy';
		
	}


	$pedido = new Pedido();
	$pedidos = $pedido->listarPedido($_GET['tipo'], $tipo_cli, $suc, $codClient, $esOutlet,	$db);
	
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga de Pedidos</title>
    <link rel="shortcut icon" href="../../css/icono.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 95px;
            overflow-x: hidden;
            overflow-y: hidden;
            height:500px;
        }
        .fixed-header {
            position: fixed;
            top: 5;
            left: 0;
            right: 0;
            z-index: 1000;
            background-color: #f8f9fa;
            padding: 10px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
			margin-right:3rem; 
			margin-left:3.8rem; 
        }
        .table-container {
            overflow-x: auto;
            margin-bottom: 20px;
			margin-right:3rem; 
			margin-left:3rem; 
        }
        #pedidosTable {
            font-size: 0.8rem;
            width: 100%;
        }
        #pedidosTable th, #pedidosTable td {
            white-space: nowrap;
            padding: 0.5rem;
        }
        .table-fixed-header {
            position: sticky;
            top: 120px;
            background-color: #e9ecef;
            z-index: 1;
        }
        .sale-badge {
            background-color: #dc3545;
            color: white;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 0.7em;
            font-weight: bold;
        }
        .search-box {
            position: relative;
        }
        .clear-search {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
        .pedido-input {
            width: 50px;
        }
        .page-title {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }
        @media (max-width: 768px) {
            body{
                height:768px;
                overflow-x: hidden;
            }
            .stock-local, .ventas-30-dias {
                display: none;
            }
            #pedidosTable {
                font-size: 0.75rem;
                width: 100%;
            }
        }
        .product-image {
            cursor: pointer;
        }
        #creditAlertContainer {
        position: absolute;
        z-index: 1000;
        width: 22%;
        }

        #creditAlertContainer .alert {
            margin-bottom: 0;
            padding: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div id="aguarde" style="display: none;">
        <h1 class="text-center">Aguarde un momento por favor
            <div class="spinner-border text-dark" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </h1>
    </div>

    <div class="fixed-header">
        <div class="container-fluid">
            <h1 class="page-title">
                <i class="fas fa-shopping-cart"></i> Carga de Pedido
            </h1>
            <div class="row align-items-center">
                <div class="col-md-4 mb-2">
                    <div class="search-box">
                        <input type="text" id="searchBox" class="form-control form-control-sm" placeholder="Buscar por código, descripción o rubro...">
                        <i class="fas fa-times clear-search" id="clearSearch"></i>
                    </div>
                </div>
                <div class="col-md-2 mb-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Total articulos</span>
                        <input type="text" id="total" class="form-control" value="0" readonly>
                    </div>
                </div>
                <div class="col-md-2 mb-2" <?php if ($suc < 100) { echo 'hidden'; } ?>>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Importe total:</span>
                        <input type="text" name="total_precio" id="totalPrecio" class="form-control" value="0" onChange="verificarCredito()">
                    </div>
                    <div id="creditAlertContainer" class="mt-2"></div>
                </div>
                <div class="row col-md-4 mb-2">
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-secondary" id="btnGrabarPedido">
                            <i class="fas fa-save"></i> Grabar
                        </button>
                        <button type="button" class="btn btn-secondary" id="btnCargarPedido">
                            <i class="fas fa-upload"></i> Cargar
                        </button>
                        <button class="btn btn-success" id="btnExport">
                            <i class="fas fa-file-excel"></i> Exportar
                        </button>
						<button class="btn btn-primary" id="btnEnviar" 
							onClick="<?= ($_SESSION['tipo'] == 'MAYORISTA') ? 'enviarMayorista()' : 'enviar()';?>">
							<i class="fas fa-cloud-upload-alt"></i> Enviar
						</button>
                        <span id="sinConexion" class="badge bg-danger ml-4" style="display: none; margin-left:1rem">SIN CONEXIÓN</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="table-container">
            <table id="pedidosTable" class="table table-striped table-hover">
                <thead class="table-fixed-header">
                    <tr>
                        <th>Foto</th>
                        <th>Codigo</th>
                        <th>Descripcion</th>
                        <th>Rubro</th>
                        <th>Stock Central</th>
                        <th class="stock-local">Stock Local</th>
                        <th class="ventas-30-dias">Venta 30 dias</th>
                        <th>Distribucion</th>
                        <th>Pedido</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody id="tabla">
                    <?php foreach ($pedidos as $v) {
                        $imageName = substr($v['COD_ARTICU'], 0, 13);
                        $imageUrl = file_exists("../../Imagenes/".$imageName.".jpg") ? "../../Imagenes/".$imageName.".jpg" : "";
                        $isSale = (substr($v['DESCRIPCIO'], -11) == '-- SALE! --');
                        $description = $isSale ? substr($v['DESCRIPCIO'], 0, -11) : $v['DESCRIPCIO'];
                    ?>
                        <tr id="trPedido">
                            <td>
                                <img src="<?= $imageUrl ?>" alt="Sin imagen" height="40" width="40" class="product-image" data-bs-toggle="modal" data-bs-target="#imageModal<?= $imageName ?>">
                            </td>
                            <td id="codArticu"><?= $v['COD_ARTICU'] ?></td>
                            <td>
                                <?= $description ?>
                                <?= $isSale ? '<span class="sale-badge">SALE</span>' : '' ?>
                            </td>
                            <td><?= $v['RUBRO'] ?></td>
                            <td id="stock"><?= (int)($v['CANT_STOCK']) ?></td>
                            <td id="cantStock" class="stock-local">0</td>
                            <td id="cantVendida" class="ventas-30-dias">0</td>
                            <td id="cant"><?= (int)($v['DISTRI']) ?></td>
                            <td>
                                <input type="number" name="cantPed[]" class="form-control form-control-sm pedido-input" value="0" min="0" id="articulo" onchange="total();verifica();precioTotal()" <?= ((int)$v['DISTRI'] > 0 ) ? 'disabled' : '' ?>>
                            </td>
                            <td id="precio">
                            <?php
                                if ($suc > 100 && $suc != 201 && $suc != 202) {
                                    if ($_GET['tipo'] == 3) {
                                        echo '$' . number_format((int)($v['PRECIO_MAYO']), 0, ',', '.');
                                    } else {
                                        if (substr($tipo_cli, 0, 1) == 'F' && $_GET['tipo'] != 3) {
                                            echo '$' . number_format((int)($v['PRECIO_FRANQ']), 0, ',', '.');
                                        } else {
                                            echo '$' . number_format((int)($v['PRECIO_MAYO']), 0, ',', '.');
                                        }
                                    }
                                }
                            ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php foreach ($pedidos as $v) {
        $imageName = substr($v['COD_ARTICU'], 0, 13);
        $imageUrl = file_exists("../../Imagenes/".$imageName.".jpg") ? "../../Imagenes/".$imageName.".jpg" : "";
    ?>
        <div class="modal fade" id="imageModal<?= $imageName ?>" tabindex="-1" aria-labelledby="imageModalLabel<?= $imageName ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel<?= $imageName ?>"><?= $v['DESCRIPCIO'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="<?= $imageUrl ?>" alt="<?= $imageName ?>.jpg - imagen no encontrada" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
 


	<?php
	$suc = $_SESSION['numsuc'];
	$codClient = $_SESSION['codClient'];
	$t_ped = $_SESSION['tipo_pedido'];
	$depo = $_SESSION['depo'];
	$talon_ped = 97;
	?>

	<script>
		let suc = '<?= $suc; ?>'
		let codClient = '<?= $codClient; ?>'
		let t_ped = '<?= $t_ped; ?>'
		let depo = '<?= $depo; ?>'
		let talon_ped = '<?= $talon_ped; ?>'
		let cupo_credito = '<?= (int)$_SESSION['cupoCredi'];  ?>'
	</script>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
	<script src="js/main.js"></script>
	<script src="js/envio.js"></script>
	<script src="js/jquery.table2excel.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
        $(document).ready(function() {
            var table = $('#pedidosTable').DataTable({
                order: [[3, 'asc'], [1, 'asc']],
                pageLength: -1,
                lengthChange: false,
                searching: false,
                info: false,
                paging: false,
                scrollY: 'calc(100vh - 150px)',
                scrollCollapse: true,
                autoWidth: false,
                columnDefs: [
                    { width: '40px', targets: 0 },
                    { width: '100px', targets: 1 },
                    { width: '200px', targets: 2 },
                    { width: '100px', targets: 3 },
                    { width: '60px', targets: [4, 5, 6, 7, 8, 9] }
                ]
            });

            $('#searchBox').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('#clearSearch').on('click', function() {
                $('#searchBox').val('').trigger('keyup');
            });

            $('#pedidosTable').on('change', '.pedido-input', updateTotals);

            function updateTotals() {
                var totalArticulos = 0;
                var totalPrecio = 0;
                $('#pedidosTable tbody tr').each(function() {
                    var cantidad = parseInt($(this).find('.pedido-input').val()) || 0;
                    var precioTexto = $(this).find('td:last').text();
                    var precio = parseFloat(precioTexto.replace(/[^\d,]/g, '').replace(',', '.')) || 0;
                    totalArticulos += cantidad;
                    totalPrecio += cantidad * precio;
                });
                $('#total').val(totalArticulos);
                
                // Formatear el precio total con separadores de miles y sin decimales
                var formattedTotalPrecio = new Intl.NumberFormat('es-AR', { 
                    style: 'currency', 
                    currency: 'ARS',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(totalPrecio);
                
                $('#totalPrecio').val(formattedTotalPrecio);
            }

            $(window).resize(function() {
                table.columns.adjust().draw();
            });
        });

    </script>
	</body>
	</html>

	<?php } ?>




