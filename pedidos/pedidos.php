<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../../login.php");
	
}elseif($_SESSION['habPedidos']==0 && $_SESSION['numsuc'] > 100){
	
	header("Location:../index.php");

}else{
		
		?>
		<!doctype html>
		<html>
		<head>
		
		<title>Carga de Pedidos</title>
		<meta charset="UTF-8"></meta>
		<link rel="shortcut icon" href="../../css/icono.jpg" />
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<link rel="stylesheet" href="style/style.css">
		<style>
		td{
			font-size:11px;
		}
		#cant{
			font-size:14px;
		}
		#stock{
			font-size:14px;
		}
		
		</style>
			
		</head>
		<body>

		<div id="aguarde" >
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
		
		
		switch($_GET['tipo']){
		case 1: $_SESSION['tipo_pedido'] = 'GENERAL'; break;
		case 2: $_SESSION['tipo_pedido'] = 'ACCESORIOS'; break;
		case 3: $_SESSION['tipo_pedido'] = 'OUTLET'; break;
		}
		
		$_SESSION['depo'] = '01';
		
		$codClient = $_SESSION['username'];
		$tipo_cli = $_SESSION['tipo'];
		
		include 'Controlador/tipoPedido.php';
		
		switch($_GET['tipo']){
		case 1: $sql = $sql1; break;
		case 2: $sql = $sql2; break;
		case 3: $sql = $sql3; break;
		}
		
		$cid = odbc_connect($dsn, $user, $pass);
		
		ini_set('max_execution_time', 300);
		$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
	

		?>

		
		

		<!--<form method="POST" action="Controlador/cargarPedidoNuevo.php" onkeypress = "return pulsar(event)">-->

		<div style="width:100%; padding-bottom:5%; margin-bottom:5%" id="pantalla">
  
		  <table class="table table-striped table-condensed table-fh table-12c" id="id_tabla">
			
		<thead>
			<tr style="font-size:smaller">
				<th style="width: 8%">FOTO</th>
				<th style="width: 10%">CODIGO</th>
				<th style="width: 25%">
					<div class="row">
						<div class="col-6">DESCRIPCION </div>
						<div class="col-6">
							<input type="text" class="form-control form-control-sm" onkeyup="myFunction()" id="textBox" name="factura" placeholder="Filtro Rapido" autofocus>
						</div>
					</div>
				</th>
				<th style="width: 12%">RUBRO</th>
				<th style="width: 10%">STOCK<br>CENTRAL</th>

			<?php if($_SESSION['tipo']!= 'MAYORISTA'){ ?>
				<th style='width: 10%'>STOCK<br>LOCAL</th>
				<th style='width: 10%'>VENTAS<br>30 DIAS</th>
			<?php } ?>
				
				<th style="width: 5%" id="distribucion" >DIST
				<span class="d-inline-block" tabindex="0" id="tool" data-toggle="tooltip" title="Articulos en distribucion, pendientes de entrega"></span>	
				</th>

				<th style="width: 5%">PEDIDO</th>
				<th style="width: 5%"><button class="btn btn-primary btn-sm" onClick="<?php if($_SESSION['tipo']=='MAYORISTA'){ echo 'enviarMayorista()' ; } else { echo 'enviar()' ;	} ?>">Enviar</button></th>
			</tr>
		</thead>
		
		<tbody  id="tabla">
		
		<?php


		while($v=odbc_fetch_array($result)){

			?>
			
			
			<?php 
			if(substr($v['DESCRIPCIO'], -11)=='-- SALE! --'){
				?>
				<tr style="font-weight:bold;color:#FE2E2E" >
				<?php
			}else{
				?>
				<tr >
				<?php
			}
			?>
			
			<td style="width: 8%"> 
			<a target="_blank" data-toggle="modal" data-target="#exampleModal<?= substr($v['COD_ARTICU'], 0, 13) ;?>" href="../../Imagenes/<?= substr($v['COD_ARTICU'], 0, 13) ;?>.jpg"><img src="../../Imagenes/<?= substr($v['COD_ARTICU'], 0, 13) ;?>.jpg" alt="Sin imagen" height="50" width="50"></a>
			</td>
			
			<div class="modal fade" id="exampleModal<?= substr($v['COD_ARTICU'], 0, 13) ;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-body" align="center">
					<img src="../../Imagenes/<?= substr($v['COD_ARTICU'], 0, 13) ;?>.jpg" alt="<?= substr($v['COD_ARTICU'], 0, 13) ;?>.jpg - imagen no encontrada" height="400" width="400">
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				  </div>
				</div>
			  </div>
			</div>
			
			<td style="width: 10%"><?= $v['COD_ARTICU'] ;?></td>

			<td style="width: 25%"><?= $v['DESCRIPCIO'] ;?></td>
			
			<td style="width: 12%"><?= $v['RUBRO'] ;?></td>
			
			<td style="width: 10%" id="stock"><?= (int)($v['CANT_STOCK']) ;?></td>

			<?php if($_SESSION['tipo']!= 'MAYORISTA'){ ?>

			<td style="width: 10%" id="cant" ><?= (int)($v['STOCK_LOCAL']) ;?></td>

			<td style="width: 10%" id="cant"><?= (int)($v['VENDIDO_LOCAL']) ;?></td>

			<?php } ?>
			
			<td style="width: 5%" id="cant"><?= (int)($v['DISTRI']) ;?></td>

			<td style="width: 5%" id="cant"><input type="text" name="cantPed[]" value="0" size="4" tabindex="1" id="articulo" class="form-control form-control-sm" onkeyup="total();verifica();precioTotal()"></td>

			<td style="width: 5%" id="precio">
				<?php 
					if($suc>100){
						if(substr($tipo_cli, 0, 1)=='F'){
							echo (int)($v['PRECIO_FRANQ']);
						}else{
							echo (int)($v['PRECIO_MAYO']);
						}
					}
				?>
			</td>
			
			</tr>

			<?php

		}

		?>
		
			<!--FILA DE MAS QUE SE PONE PARA QUE EL TOTAL NO PISE AL ULTIMO REGISTRO-->
			<tr style="font-size:smaller" id="ultimo"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
		
		</tbody>
		
		

		</table>
		</div>
		
		
		
		<!--</form>-->

		
		<div class="mt-2 text-center fixed fixed-bottom bg-white" style="height: 30px!important; background-color: white;" id="pantalla">

			<a> <strong>Total de articulos:</strong> </a> <input name="total_todo" size="3" id="total" value="0" type="text">
			<?php if($suc>100){ ?> 
			
				<a> <strong>Importe total:</strong> </a> <input name="total_precio" size="3" id="totalPrecio" value="0" type="text" onChange="verificarCredito()">
				<a id="cupoCreditoExcedido"></a>
			<?php } ?>
			
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

		var cupo_credito = '<?= $_SESSION['cupoCredi']; ?>'
		

		

		</script>

		<script src="js/main.js"></script>
		<script src="js/envio.js"></script>
		
		</html>

		<?php
	
}
?>

