<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../../login.php");

}else{
	$_SESSION['cargaPedido'] = 1;
	if($_SESSION['nuevoPedido']==0 && $_SESSION['cargaPedido']==1){
		?>
		<!doctype html>
		<html>
		<head>
		<meta charset="utf-8">
		<title>Carga de Pedidos</title>
		<link rel="shortcut icon" href="icono.jpg" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		
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
			
			
			function verifica() {
				
				var x = document.querySelectorAll("#id_tabla input[name='cantPed[]']");
				var y = document.querySelectorAll("#id_tabla #id_stock");

				var i;
				for (i = 0; i < x.length; i++) {
					if( parseInt(x[i].value) > parseInt(y[i].value) ){
						alert("El valor ingresado es incorrecto");
					}
				}
		
			};
			
		
		</script>
		
		
		
		<style type="text/css">
		#encabezado{
			position:fixed;
			top:0;
			width:100%;
			height:60px;
			background-color:#333;
			color:#FFFFFF;
			
		}
		</style>
		
		</head>
		<body>

		</br></br>
		<div class="container-fluid">
		<?php

		$dsn = "1 - CENTRAL";
		$user = "Axoft";
		$pass = "Axoft";
		$suc = $_SESSION['numsuc'];
		
		$_SESSION['tipo_pedido'] = 'GENERAL';
		$_SESSION['depo'] = '01';
		
		
		$cid = odbc_connect($dsn, $user, $pass);


		$sql="
			SET DATEFORMAT YMD

			SELECT COD_ARTICU, 
			CASE WHEN COD_ARTICU IN (SELECT COD_ARTICU FROM MAESTRO_DESTINOS WHERE LIQUIDACION = 'SI') THEN DESCRIPCIO+' -- SALE! --' ELSE DESCRIPCIO END DESCRIPCIO, 
			B.RUBRO, CANT_STOCK FROM
			(
				SELECT COD_ARTICU, DESCRIPCIO, CASE WHEN CANT_STOCK < 0 THEN 0 ELSE CANT_STOCK END CANT_STOCK FROM
				(
					SELECT A.COD_ARTICU, DESCRIPCIO, CASE WHEN B.CANT_PEND IS NULL THEN A.CANT_STOCK ELSE CANT_STOCK-B.CANT_PEND END CANT_STOCK
					FROM
						(SELECT A.COD_ARTICU, A.DESCRIPCIO, A.CANT_STOCK FROM
						(SELECT A.COD_ARTICU, C.DESCRIPCIO, A.CANT_STOCK FROM STA19 A INNER JOIN STA11 C ON A.COD_ARTICU = C.COD_ARTICU WHERE A.COD_DEPOSI = '03' 
						AND (A.COD_ARTICU LIKE 'X%' OR A.COD_ARTICU = 'OHGIFT') AND A.CANT_STOCK > 0 )A  )A
					LEFT JOIN 
						(SELECT COD_ARTICU, SUM(CANT_A_DES)CANT_PEND FROM GVA03 A INNER JOIN GVA21 B ON A.NRO_PEDIDO = B.NRO_PEDIDO AND A.TALON_PED = B.TALON_PED
					WHERE A.TALON_PED = 96 AND B.ESTADO = 2 AND B.FECHA_PEDI > (GETDATE()-15) GROUP BY COD_ARTICU HAVING SUM(CANT_A_DES) > 0)B
					ON A.COD_ARTICU = B.COD_ARTICU
				)A
			)A
			INNER JOIN 
				(SELECT A.CODE, B.DESCRIP RUBRO FROM STA11ITC A INNER JOIN STA11FLD B ON A.IDFOLDER = B.IDFOLDER WHERE B.DESCRIP NOT LIKE '[_]%'
				--AND B.IDFOLDER IN (6, 8, 9, 12, 15)
				)B
			ON A.COD_ARTICU = B.CODE
			WHERE CANT_STOCK > 0
			--AND COD_ARTICU NOT IN (SELECT COD_ARTICU FROM MAESTRO_DESTINOS WHERE DESTINO LIKE '%DISCONTINUO%' OR DESTINO LIKE '%GUARDAR%')
			ORDER BY 3, 1
		
		";

		$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

		?>


		<form method="POST" action="nuevoPedidoRotacion.php" onkeypress = "return pulsar(event)">

		
		<table class="table table-striped" id="id_tabla">

		
		
		<tr class="row" id="encabezado">
		
		
			<td class="col-md" style="margin-left:4%">CODIGO</td>
			
			<td></td>

			<td class="col-md" style="margin-left:10%">DESCRIPCION</td>
			
			<td class="col-md" style="margin-left:20%">RUBRO</td>
			
			<td></td>
			
			<td></td>

			<td class="col-md" style="margin-left:13%;font-size:smaller" align="center">STOCK DEP 03</td>
			
			<td class="col-md" style="font-size:smaller">PEDIDO</td></div>

			<td class="col-md" ><input type="submit" value="Enviar" class="btn btn-primary btn-sm"></td>
		
		
		</tr>


		<?php


		while($v=odbc_fetch_array($result)){

			?>

			<div style="margin-top:20px">
				

			<td> <?php echo $v['COD_ARTICU'] ;?></td>
			
			<td><input name="codArt[]" value="<?php echo $v['COD_ARTICU'] ;?>"  hidden></td>

			<td align="left"><?php echo $v['DESCRIPCIO'] ;?></td>
			
			<td align="left"><?php echo $v['RUBRO'] ;?></td>

			<td><input name="rubro[]" value="<?php echo $v['RUBRO'] ;?>"  hidden></td>
			
			<td><input name="stock[]" value="<?php echo $v['CANT_STOCK'] ;?>"  hidden></td>
			
			<td align="left" id="stock"><?php echo (int)($v['CANT_STOCK']) ;?></td>
			
			<td ><input type="text" name="cantPed[]" value="0" size="2" tabindex="1" id="articulo" onChange="total();verifica()"></td>

			<td></td>
			
			<td></td>
			
			</tr>
			</div>

			<?php

		}

		?>



		</table>
		
		</form>

		</div>
		</br></br></br>
		<div align="center"><a>Total de articulos:</a><input name="total_todo" size="4" id="total" value="0" type="text"></div>
		</br></br></br>
		
				
		</body>
		</html>

		<?php
	}

	else{
		
		header("Location:login.php");
	}
	
}
?>

