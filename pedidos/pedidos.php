<?php
session_start();

if (!isset($_SESSION['username'])) {

	header("Location:../../login.php");
} elseif ($_SESSION['habPedidos'] == 0 && $_SESSION['numsuc'] > 100) {

	header("Location:../index.php");
} else {

?>
	<!doctype html>
	<html>

	<head>

		<title>Carga de Pedidos</title>
		<meta charset="UTF-8">
		</meta>
		<link rel="shortcut icon" href="../../css/icono.jpg" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


		<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

		<link rel="stylesheet" href="style/style.css">

		<style>
			td {
				font-size: 11px;
			}

			#cant {
				font-size: 14px;
			}

			#stock {
				font-size: 14px;
			}

			#textBox, #total
			{
				border: 1px solid grey; 
				border-radius: 5px;
				padding: 5px;
			}

			#textBox
			{
				width: 250px;
			}

			#total
			{
				width: 60px;
				text-align: center;
			}

			#btnExport {
				float: right;
			}

			#articulo{
				width: 60px;
			}	
			
		</style>

	</head>

	<body>

		<div id="aguarde">
			<h1 class="text-center">Aguarde un momento por favor
				<div class="spinner-border text-dark" role="status">
					<span class="sr-only">Loading...</span>
				</div>
			</h1>
		</div>

		<?php

		$dsn = "1 - CENTRAL";
		$user = "sa";
		$pass = "Axoft1988";
		$suc = $_SESSION['numsuc'];


		switch ($_GET['tipo']) {
			case 1:
				$_SESSION['tipo_pedido'] = 'GENERAL';
				break;
			case 2:
				$_SESSION['tipo_pedido'] = 'ACCESORIOS';
				break;
			case 3:
				$_SESSION['tipo_pedido'] = 'OUTLET';
				break;
		}

		$_SESSION['depo'] = '01';

		$codClient = $_SESSION['username'];
		$tipo_cli = $_SESSION['tipo'];

		include 'Controlador/tipoPedido.php';

		switch ($_GET['tipo']) {
			case 1:
				$sql = $sql1;
				break;
			case 2:
				$sql = $sql2;
				break;
			case 3:
				$sql = $sql3;
				break;
		}

		$cid = odbc_connect($dsn, $user, $pass);

		ini_set('max_execution_time', 300);
		$result = odbc_exec($cid, $sql) or die(exit("Error en odbc_exec"));


		?>




		<!--<form method="POST" action="Controlador/cargarPedidoNuevo.php" onkeypress = "return pulsar(event)">-->

		<div style="width:98%; height:50%; padding-bottom:5%; margin-left:10px" id="pantalla">


					<div id="menu" class="row mt-3 mb-2" >
						<div class="col-4">
							<a> <strong>Búsqueda rápida</strong> </a>
								<input type="text" onkeyup="myFunction()" id="textBox" name="factura" placeholder="Buscar..." autofocus>
						</div>
						<div class="col-2">
							<a> <strong>Total de articulos</strong> </a> 
							<input name="total_todo" size="3" id="total" value="0" type="text" readonly>
						</div>
						<div class="col-2"
							<?php if ($suc < 100) { echo 'hidden'; } ?>
						>
							<a> <strong>Importe total:</strong> </a> 
							<input name="total_precio" size="3" id="totalPrecio" value="0" type="text" onChange="verificarCredito()">
							<a id="cupoCreditoExcedido"></a>
							
						</div>
					
					<div class="col-2">
						<button class="btn btn-success btn_exportar" id="btnExport"><i class="fa fa-file-excel-o"></i> Exportar</button>
					</div>				
					<div class="col-2">
						<button class="btn btn-primary" onClick="<?php if ($_SESSION['tipo'] == 'MAYORISTA') {
													echo 'enviarMayorista()';
												} else {
													echo 'enviar()';
												} ?>"><i class="fa fa-cloud-upload"></i> Enviar</button>
					</div>				
				</div>
		

			<table class="table table-striped table-condensed table-fh table-12c" id="id_tabla">

				<thead class="bg-secondary text-white">


					<tr style="font-size:smaller">
						<th>FOTO</th>
						<th>CODIGO</th>
						<th style="width: 80px;">DESCRIPCION</th>
						<th style="width: 50px;">RUBRO</th>
						<th>STOCK<br>CENTRAL</th>

						<?php if ($_SESSION['tipo'] != 'MAYORISTA') { ?>
							<th>STOCK<br>LOCAL</th>
							<th>VENTAS<br>30 DIAS</th>
						<?php } ?>

						<th id="distribucion">DIST
							<span class="d-inline-block" tabindex="0" id="tool" data-toggle="tooltip" title="Articulos en distribucion, pendientes de entrega"></span>
						</th>

						<th>PEDIDO</th>
						<th>PRECIO</th>

					</tr>
				</thead>

				<tbody id="tabla">

					<?php


					while ($v = odbc_fetch_array($result)) {

					?>


						<?php
						if (substr($v['DESCRIPCIO'], -11) == '-- SALE! --') {
						?>
							<tr style="font-weight:bold;color:#FE2E2E">
							<?php
						} else {
							?>
							<tr>
							<?php
						}
							?>

							<td>
								<a target="_blank" data-toggle="modal" data-target="#exampleModal<?= substr($v['COD_ARTICU'], 0, 13); ?>" href="../../Imagenes/<?= substr($v['COD_ARTICU'], 0, 13); ?>.jpg"><img src="../../Imagenes/<?= substr($v['COD_ARTICU'], 0, 13); ?>.jpg" alt="Sin imagen" height="50" width="50"></a>
							</td>

							<div class="modal fade" id="exampleModal<?= substr($v['COD_ARTICU'], 0, 13); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-body" align="center">
											<img src="../../Imagenes/<?= substr($v['COD_ARTICU'], 0, 13); ?>.jpg" alt="<?= substr($v['COD_ARTICU'], 0, 13); ?>.jpg - imagen no encontrada" height="400" width="400">
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
										</div>
									</div>
								</div>
							</div>

							<td><?= $v['COD_ARTICU']; ?></td>

							<td style="width: 80px;"><?= $v['DESCRIPCIO']; ?></td>

							<td style="width: 50px;"><?= $v['RUBRO']; ?></td>

							<td id="stock"><?= (int)($v['CANT_STOCK']); ?></td>

							<?php if ($_SESSION['tipo'] != 'MAYORISTA') { ?>

								<td id="cant"><?= (int)($v['STOCK_LOCAL']); ?></td>

								<td id="cant"><?= (int)($v['VENDIDO_LOCAL']); ?></td>

							<?php } ?>

							<td id="cant"><?= (int)($v['DISTRI']); ?></td>

							<td id="cant"><input type="number" name="cantPed[]" value="0" size="4" tabindex="1" id="articulo" class="form-control form-control-sm" onchange="total();verifica();precioTotal()"></td>

							<td id="precio">
								<?php

								if ($suc > 100) {

									if ($_GET['tipo'] == 3) {

										echo (int)($v['PRECIO_MAYO']);
									} else {

										if (substr($tipo_cli, 0, 1) == 'F' && $_GET['tipo'] != 3) {
											echo (int)($v['PRECIO_FRANQ']);
										} else {
											echo (int)($v['PRECIO_MAYO']);
										}
									}
								}
								?>
							</td>

							</tr>

						<?php

					}

						?>

						<!--FILA DE MAS QUE SE PONE PARA QUE EL TOTAL NO PISE AL ULTIMO REGISTRO-->
						<tr style="font-size:smaller" id="ultimo">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>

				</tbody>



			</table>
		</div>



		<!--</form>-->

		

		</div>


	</body>


	<?php
	$suc = $_SESSION['numsuc'];
	$codClient = $_SESSION['codClient'];
	$t_ped = $_SESSION['tipo_pedido'];
	$depo = $_SESSION['depo'];
	$talon_ped = 97;
	?>

	<script>
		var suc = '<?= $suc; ?>'
		var codClient = '<?= $codClient; ?>'
		var t_ped = '<?= $t_ped; ?>'
		var depo = '<?= $depo; ?>'
		var talon_ped = '<?= $talon_ped; ?>'

		var cupo_credito = '<?= (int)$_SESSION['cupoCredi'];  ?>'
	</script>

	<script src="js/main.js"></script>
	<script src="js/envio.js"></script>

	</html>

<?php

}
?>

<script src="js/jquery.table2excel.js"></script>
