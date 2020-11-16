<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:login.php");

}else{
	$_SESSION['cargaPedido'] = 1;
	if($_SESSION['nuevoPedido']==0 && $_SESSION['cargaPedido']==1){
		?>
		<!doctype html>
		<html>
		<head>
			<title>Carga de Pedidos</title>
			<?php include '../../../css/header.php'?>
		</head>

		<body>

		<div>
		<?php

		$dsn = "1 - CENTRAL";
		$user = "Axoft";
		$pass = "Axoft";
		$suc = $_SESSION['numsuc'];
		$codClient = $_SESSION['codClient'];
		
		$_SESSION['tipo_pedido'] = 'GENERAL';
		$_SESSION['depo'] = '01';
		
		$cid = odbc_connect($dsn, $user, $pass);


		$sql="
		SET DATEFORMAT YMD

		EXEC SJ_TIPO_PEDIDO_1 200, '$codClient'
		
		";

		$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

		?>

		<form method="POST" action="nuevoPedidoMayorista.php" onkeypress = "return pulsar(event)">
		
		<div style="width:100%; padding-bottom:5%; margin-bottom:5%" >
  
		  <table class="table table-striped table-fh table-12c" id="id_tabla">
			
			<thead>
				<tr style="font-size:smaller">
					<th style="width: 8%">FOTOS</th>
					<th style="width: 12%">CODIGO</th>
					<th style="width: 1%"></th>
					<th style="width: 20%">DESCRIPCION</th>
					<th style="width: 10%">RUBRO</th>
					<th style="width: 1%"></th>
					<th style="width: 1%"></th>
					<th style="width: 5%">STOCK<br>CENTRAL</th>
					<th style="width: 5%">PRECIO</th>
					<th style="width: 5%">PEDIDO</th>
					<th style="width: 10%"><input type="submit" value="Enviar" class="btn btn-primary btn-sm"></th>
				</tr>
			</thead>
		
		<tbody>
		
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
				<tr style="font-size:smaller">
				<?php
			}
			?>

			<td style="width: 8%"> 
			<a target="_blank" href="../../../Imagenes/<?php echo substr($v['COD_ARTICU'], 0, 13) ;?>.jpg"><img src="../../../Imagenes/<?php echo substr($v['COD_ARTICU'], 0, 13) ;?>.jpg" alt="" height="50" width="50"></a>
			</td>			

			<td style="width: 12%"> <?php echo $v['COD_ARTICU'] ;?></td>
			<td style="width: 1%"><input name="codArt[]" value="<?php echo $v['COD_ARTICU'] ;?>"  hidden></td>
			<td style="width: 20%"><?php echo $v['DESCRIPCIO'] ;?></td>
			<td style="width: 10%"><?php echo $v['RUBRO'] ;?></td>
			<td style="width: 1%"><input name="rubro[]" value="<?php echo $v['RUBRO'] ;?>"  hidden></td>
			<td style="width: 1%"><input name="stock[]" value="<?php echo $v['CANT_STOCK'] ;?>"  hidden></td>
			<td style="width: 5%" id="stock"><?php echo (int)($v['CANT_STOCK']) ;?></td>
			<td style="width: 5%" id="precio"><?php echo (int)($v['PRECIO_MAYO']) ;?></td>
			<td style="width: 5%"><input type="text" name="cantPed[]" value="0" size="4" tabindex="1" id="articulo" onChange="total();verifica();precioTotal()"></td>
			<td style="width: 10%"></td>
			
			</tr>
			

			<?php

		}

		?>
		
		<!--FILA DE MAS QUE SE PONE PARA QUE EL TOTAL NO PISE AL ULTIMO REGISTRO-->
			<tr style="font-size:smaller" ><td style="width: 8%"></td></td></tr>

		</tbody>
		
		

		</table>
		</div>
		
		
		
		</form>

		<div class="mt-2 text-center fixed fixed-bottom bg-white" style="height: 30px!important; background-color: white;" >
			<a> <strong>Total de articulos:</strong> </a> <input name="total_todo" size="3" id="total" value="0" type="text">
			<a> <strong>Importe total:</strong> </a> <input name="total_precio" size="3" id="totalPrecio" value="0" type="text">
		</div>
		
				
		</body>
		
		
		
		<script>
			
			function pulsar(e) {
			tecla = (document.all) ? e.keyCode : e.which;
			return (tecla != 13);
			}
			
				
			function total() {
				var suma = 0;
				var x = document.querySelectorAll("#id_tabla input[name='cantPed[]']"); //tomo todos los input con name='cantProd[]'

				var i;
				for (i = 0; i < x.length; i++) {
					suma += parseInt(0+x[i].value); //acá hago 0+x[i].value para evitar problemas cuando el input está vacío, si no tira NaN
				}

				// ni idea dónde lo vas a mostrar ese dato, yo puse un input, pero puede ser cualquier otro elemento
				document.getElementById('total').value = suma;
			};
			

			function precioTotal() {
				var precioTodos = 0;
				var p = document.querySelectorAll("#id_tabla #precio"); 
				var x = document.querySelectorAll("#id_tabla input[name='cantPed[]']");
				


				var i;
				for (i = 0; i < p.length; i++) {
					precioTodos += parseInt(0+p[i].innerHTML * x[i].value); //acá hago 0+x[i].value para evitar problemas cuando el input está vacío, si no tira NaN
				}

				// // ni idea dónde lo vas a mostrar ese dato, yo puse un input, pero puede ser cualquier otro elemento
				document.getElementById('totalPrecio').value = precioTodos;
			};
			
			function verifica() {
				
				var x = document.querySelectorAll("#id_tabla input[name='cantPed[]']");
				var y = document.querySelectorAll("#id_tabla #stock");

				var i;
				for (i = 0; i < x.length; i++) {
					if( parseInt(x[i].value) > parseInt(y[i].innerHTML) ){
						alert("El valor ingresado es incorrecto");
						x[i].value = 0;
					}
				}

		
			};
			
		
		</script>
		
		</html>

		<?php
	}

	else{
		
		header("Location:login.php");
	}
	
}
?>

