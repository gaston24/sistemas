<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../login.php");

}else{
	$_SESSION['cargaPedido'] = 1;
	if($_SESSION['nuevoPedido']==0 && $_SESSION['cargaPedido']==1){
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
			
		</head>
		<body>

		<?php

		$dsn = "1 - CENTRAL";
		$user = "sa";
		$pass = "Axoft1988";
		$suc = $_SESSION['numsuc'];
		
		$_SESSION['tipo_pedido'] = 'GENERAL';
		$_SESSION['depo'] = '01';
		
		$codClient = $_SESSION['username'];
		
		$cid = odbc_connect($dsn, $user, $pass);

		switch ($codClient) {
			case 'GTWEB':
				$dep = '09';
				break;
			case 'GTDAF':
				$dep = '12';
				break;
			case 'GTMELI':
				$dep = '01';
				break;
		}

		$tipo_pedido = $_GET['tipo'];
		
		$sql="
		SJ_SP_PEDIDOS_ECOMMERCE_VER '$dep', '$codClient', $tipo_pedido
		";
		
		ini_set('max_execution_time', 300);
		$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

		?>


		<div style="width:100%; padding-bottom:5%; margin-bottom:5%" >
  
		  <table class="table table-striped table-fh table-12c" id="id_tabla">
			
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
				<th style='width: 10%'>STOCK<br>LOCAL</th>
				<th style='width: 10%'>VENTAS<br>30 DIAS</th>
				<th style="width: 5%">DIST</th>
				<th style="width: 5%">PEDIDO</th>
				<th style="width: 5%"><button class="btn btn-primary btn-sm" onClick="enviar()">Enviar</button></th>
			</tr>
		</thead>
		
		<tbody  id="tabla">
		<?php


		while($v=odbc_fetch_array($result)){

			?>
			
			
			<?php 
			if(substr($v['DESCRIPCIO'], -11)=='-- SALE! --'){
				?>
				<tr style="font-size:smaller;font-weight:bold;color:#FE2E2E" >
				<?php
			}else{
				?>
				<tr style="font-size:smaller" >
				<?php
			}
			?>
			
			<td style="width: 8%"> 
			<a target="_blank" data-toggle="modal" data-target="#exampleModal<?php echo substr($v['COD_ARTICU'], 0, 13) ;?>" href="../../Imagenes/<?php echo substr($v['COD_ARTICU'], 0, 13) ;?>.jpg"><img src="../../Imagenes/<?php echo substr($v['COD_ARTICU'], 0, 13) ;?>.jpg" alt="" height="50" width="50"></a>
			</td>
			
			<div class="modal fade" id="exampleModal<?php echo substr($v['COD_ARTICU'], 0, 13) ;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-body" align="center">
					<img src="../../Imagenes/<?php echo substr($v['COD_ARTICU'], 0, 13) ;?>.jpg" alt="<?php echo substr($v['COD_ARTICU'], 0, 13) ;?>.jpg - imagen no encontrada" height="400" width="400">
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				  </div>
				</div>
			  </div>
			</div>
			
			<td style="width: 10%"><?php echo $v['COD_ARTICU'] ;?></td>

			<td style="width: 25%"><?php echo $v['DESCRIPCIO'] ;?></td>
			
			<td style="width: 12%"><?php echo $v['RUBRO'] ;?></td>
			
			<td style="width: 10%" id="stock"><?php echo (int)($v['CANT_STOCK']) ;?></td>
			
			<td style="width: 10%" id="cant" ><?php echo (int)($v['STOCK_LOCAL']) ;?></td>
			
			<td style="width: 10%" id="cant"><?php echo (int)($v['VENDIDO_LOCAL']) ;?></td>
			
			<td style="width: 5%" id="cant"><?php echo (int)($v['DISTRI']) ;?></td>

			<td style="width: 5%" id="cant"><input type="text" name="cantPed[]" value="<?php echo (int)($v['CANT_SUG']) ;?>" size="4" tabindex="1" id="articulo" class="form-control form-control-sm" onkeyup="total();verifica()"></td>

			<td style="width: 5%"></td>
			
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

		
		<div class="mt-2 text-center fixed fixed-bottom bg-white" style="height: 30px!important; background-color: white;" >
			<a> <strong>Total de articulos:</strong> </a> <input name="total_todo"  size="3" id="total" value="0" type="text">
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
		var suc = '<?php echo $suc; ?>'
		var codClient = '<?php echo $codClient; ?>'
		var t_ped = '<?php echo $t_ped; ?>'
		var depo = '<?php echo $depo; ?>'
		var talon_ped = '<?php echo $talon_ped; ?>'
		</script>
		
		<script src="js/main.js"></script>
		<script src="js/envio.js"></script>
		</html>

		<?php
	}

	else{
		
		header("Location:login.php");
	}
	
}
?>

