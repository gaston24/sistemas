<?php 

require_once $_SERVER['DOCUMENT_ROOT']."/sistemas/class/conexion.php";
require_once "../class/Pedidos.php";

session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../../login.php");
}else{
	
	$permiso = $_SESSION['permisos'];



	if(!isset($_GET['desde'])){
		// $ayer = date('Y-m').'-'.strright(('0'.((date('d')))),2);
		$ayer = date('Y-m') . '-' . str_pad(date('d') - 1, 2, '0', STR_PAD_LEFT);

	}else{
		$desde = $_GET['desde'];
		$hasta = $_GET['hasta'];
	}

	?>
	<!DOCTYPE HTML>

	<html>
		<head>

			<title>GUIAS POR LOCAL</title>	
			<?php include '../../assets/css/header.php'; ?>
			<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
        	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">
		</head>
		<body>	




			<form action="" id="sucu" style="margin:20px">
			Elija sucursal:
			<select name="sucursal" form="sucu" >

				<option value="%" <?= (isset($_GET['sucursal']) && $_GET['sucursal'] == "%" ? "selected" : "") ?>>Todos</option> 
				<option value="FRBAUD" <?= (isset($_GET['sucursal']) && $_GET['sucursal'] == "FRBAUD" ? "selected" : "") ?>>BAULERA</option> 
				<option value="FRORCE" <?= (isset($_GET['sucursal']) && $_GET['sucursal'] == "FRORCE" ? "selected" : "") ?>>VELEZ</option> 
				<option value="FRORIG" <?= (isset($_GET['sucursal']) && $_GET['sucursal'] == "FRORIG" ? "selected" : "") ?>>DINO</option> 
				<option value="FRORNC" <?= (isset($_GET['sucursal']) && $_GET['sucursal'] == "FRORNC" ? "selected" : "") ?>>NUEVO CENTRO</option> 
				<option value="FRORSJ" <?= (isset($_GET['sucursal']) && $_GET['sucursal'] == "FRORSJ" ? "selected" : "") ?>>SAN JUAN</option> 
				<option value="FRPASJ" <?= (isset($_GET['sucursal']) && $_GET['sucursal'] == "FRPASJ" ? "selected" : "") ?>>PASEO DEL JOCKEY</option> 

				
			
			</select >

			
			Desde
			<input type="date" name="desde" value="<?php if(!isset($_GET['desde'])){ echo $ayer; } else { echo $desde ; }?>"></input>
			
			Hasta
			<input type="date" name="hasta" value="<?php if(!isset($_GET['hasta'])){ echo $ayer; } else { echo $hasta ; }?>"></input>
			
			
			
				<input type="submit" value="Consultar" class="btn btn-primary">
			</form>



			<?php

			if(isset ($_GET['sucursal'])){

				$suc = $_GET['sucursal'];
					

					$suc = $_GET['sucursal'];
					$desde = $_GET['desde'];
					$hasta = $_GET['hasta'];


					$pedidos = new Pedido();
					$data = $pedidos->traerHistorialPedidos($desde, $hasta , $suc);



			?>
					<div class="container">
						<table class="table table-striped" id="tablaHistoriaPedidos" >
							<thead>

								<tr >

										<th class="col-"><h4>CLIENTE</h4></th>
								
										<th class="col-"><h4>FECHA</h4></th>
								
										<th class="col-"><h4>PEDIDO</h4></th>
										
										<th class="col-"><h4>OBSERVAC</h4></th>
										
										<th class="col-"><h4>CANT</h4></th>  

										
								</tr>
							</thead>
							<tbody>
							
								
								<?php

							
								foreach ($data as $key => $v) {

								?>

								
								<tr >

										<td class="col-"><?=  $v['COD_CLIENT'] ;?></a></td>
								
										<td class="col-"><?=  $v['FECHA']->format("Y-m-d") ;?></a></td>
										
										<td class="col-"><a href="detallePed.php?pedido=<?= $v['NRO_PEDIDO'] ;?>&suc=<?= $v['COD_CLIENT'] ;?>&desde=<?= $desde ;?>&hasta=<?= $hasta ;?>"><?= $v['NRO_PEDIDO'] ;?></a></td>
										
										<td class="col-"><?= $v['LEYENDA_1'] ;?></a></td>
										
										<td class="col-"><?= $v['CANT'] ;?></a></td>

										

								</tr>

								
								<?php

								}

								?>

							</tbody>
										
						</table>
					</div>

		</body>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
		<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
		<script>
			 $('#tablaHistoriaPedidos').DataTable({
				"bLengthChange": true,
				"language": {
							"lengthMenu": "mostrar _MENU_ registros",
							"info":           "Mostrando registros del _START_ al _END_ de un total de  _TOTAL_ registros",
							"paginate": {
								"next":       "Siguiente",
								"previous":   "Anterior"
							},

				},
			
				
				"bInfo": true,
				"aaSorting": false,
				'columnDefs': [
					{
						"targets": "_all", 
						"className": "text-center",
						"sortable": false,
				
					},
				],
				"oLanguage": {
			
					"sSearch": "Busqueda rapida:",
					"sSearchPlaceholder" : "Sobre cualquier campo"
					
			
				},
			});

		</script>
		
	</html>


		<?php
		}
}
?>
