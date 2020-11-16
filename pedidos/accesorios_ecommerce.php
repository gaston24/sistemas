<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../login.php");

}else{
	
	if($_SESSION['nuevoPedido']==0 && $_SESSION['cargaPedido']==1){
		?>
		<!doctype html>
		<html>
		<head>
		
		<title>Carga de Pedidos</title>
		<?php include '../../css/header.php'; ?>
		
		
		
		
		
		
		</head>
		<body>

		<?php

		$dsn = "1 - CENTRAL";
		$user = "Axoft";
		$pass = "Axoft";
		$suc = $_SESSION['numsuc'];
		
		$_SESSION['tipo_pedido'] = 'ACCESORIOS';
		$_SESSION['depo'] = '01';
		
		$codClient = $_SESSION['username'];
		
		$cid = odbc_connect($dsn, $user, $pass);


		$sql="
		SET DATEFORMAT YMD
		
		SELECT A.*, ISNULL(B.CANT_SUG, 0) CANT_SUG FROM

		(

		
		SELECT * FROM
		
		(
		
		SELECT COD_ARTICU, DESCRIPCIO, RUBRO, (CANT_STOCK- ( CASE WHEN CANT_TRANSF IS NULL THEN 0 ELSE CANT_TRANSF END ) )CANT_STOCK, STOCK_LOCAL, VENDIDO_LOCAL, DISTRI
		FROM 
		(
				
		SELECT A.COD_ARTICU, DESCRIPCIO, RUBRO, CANT_STOCK, B.CANT_TRANSF, STOCK_LOCAL, VENDIDO_LOCAL, CASE WHEN DIST IS NULL THEN 0 ELSE DIST END DISTRI
		FROM
		(	
			SELECT A.COD_ARTICU, 
			CASE WHEN A.COD_ARTICU IN (SELECT COD_ARTICU FROM MAESTRO_DESTINOS WHERE LIQUIDACION = 'SI') THEN DESCRIPCIO+' -- SALE! --' ELSE DESCRIPCIO END DESCRIPCIO, 
			B.RUBRO, CAST(CANT_STOCK AS INT)CANT_STOCK, CAST(STOCK_LOCAL AS INT)STOCK_LOCAL, VENDIDO_LOCAL, CAST(C.CANT_PEDID AS INT) DIST FROM
			(
				SELECT COD_ARTICU, DESCRIPCIO, CASE WHEN CANT_STOCK < 0 THEN 0 ELSE CANT_STOCK END CANT_STOCK, STOCK_LOCAL, VENDIDO_LOCAL FROM
				(
					SELECT A.COD_ARTICU, DESCRIPCIO, CASE WHEN B.CANT_PEND IS NULL THEN A.CANT_STOCK ELSE CANT_STOCK-B.CANT_PEND END CANT_STOCK, 
					STOCK_LOCAL, VENDIDO_LOCAL 
					FROM
						(SELECT A.COD_ARTICU, A.DESCRIPCIO, A.CANT_STOCK, CASE WHEN B.CANT_STOCK IS NULL THEN 0 ELSE B.CANT_STOCK END STOCK_LOCAL, 
						CASE WHEN B.VENDIDO IS NULL THEN 0 ELSE B.VENDIDO END VENDIDO_LOCAL FROM
						(SELECT A.COD_ARTICU, C.DESCRIPCIO, A.CANT_STOCK FROM STA19 A INNER JOIN STA11 C ON A.COD_ARTICU = C.COD_ARTICU WHERE A.COD_DEPOSI = '01' 
						AND (A.COD_ARTICU LIKE 'X%' OR A.COD_ARTICU = 'OHGIFT') AND A.CANT_STOCK > 0 )A LEFT JOIN (SELECT * FROM SJ_CARGA_PEDIDOS_ECOMMERCE)B 
					ON A.COD_ARTICU = B.COD_ARTICU COLLATE Modern_Spanish_CI_AI )A
					LEFT JOIN 
						(SELECT COD_ARTICU, CAST(SUM(CANT_PEND) AS FLOAT)CANT_PEND FROM (
						--ARTICULOS EN PEDIDOS PENDIENTES DEL DEPOSITO 01 DE LOS ULTIMOS 15 DIAS --LOCALES Y FRANQUICIAS
						SELECT COD_ARTICU, SUM(CANT_A_DES)CANT_PEND FROM GVA03 A INNER JOIN GVA21 B ON A.NRO_PEDIDO = B.NRO_PEDIDO AND A.TALON_PED = B.TALON_PED 
						WHERE B.ESTADO = 2 AND B.COD_CLIENT LIKE '[GF]%' AND B.FECHA_PEDI > (GETDATE()-15) AND B.COD_SUCURS = '01' GROUP BY COD_ARTICU HAVING SUM(CANT_A_DES) > 0
						UNION ALL 
						--ARTICULOS EN PEDIDOS PENDIENTES DEL DEPOSITO 01 DE LOS ULTIMOS 45 DIAS --MAYORISTAS
						SELECT COD_ARTICU, SUM(CANT_A_DES)CANT_PEND FROM GVA03 A INNER JOIN GVA21 B ON A.NRO_PEDIDO = B.NRO_PEDIDO AND A.TALON_PED = B.TALON_PED 
						WHERE B.ESTADO = 2 AND B.COD_CLIENT LIKE '[M]%' AND B.FECHA_PEDI > (GETDATE()-45) AND B.COD_SUCURS = '01' GROUP BY COD_ARTICU HAVING SUM(CANT_A_DES) > 0
						UNION ALL 
						--ARTICULOS INGRESADOS EN LOS ULTIMOS 3 DIAS, MENOS PACKAGING
						SELECT B.COD_ARTICU, SUM(B.CANTIDAD)CANT_PEND FROM STA14 A INNER JOIN STA20 B ON A.NCOMP_IN_S = B.NCOMP_IN_S AND A.TCOMP_IN_S = B.TCOMP_IN_S
						WHERE A.FECHA_MOV >= GETDATE()-3 AND N_COMP LIKE 'R%' AND A.TCOMP_IN_S = 'RP' AND B.COD_ARTICU NOT LIKE 'XP%' GROUP BY B.COD_ARTICU
						)A GROUP BY COD_ARTICU)B
				ON A.COD_ARTICU = B.COD_ARTICU
				)A
			)A
			INNER JOIN 
				(SELECT A.CODE, B.DESCRIP RUBRO FROM STA11ITC A INNER JOIN STA11FLD B ON A.IDFOLDER = B.IDFOLDER WHERE B.DESCRIP NOT LIKE '[_]%'
				AND B.IDFOLDER IN (2, 4, 5, 7, 10, 14, 16, 18, 19, 21, 31))B
				ON A.COD_ARTICU = B.CODE
			LEFT JOIN
				(SELECT B.COD_ARTICU, SUM(B.CANT_PEDID) CANT_PEDID FROM GVA21 A INNER JOIN GVA03 B ON A.NRO_PEDIDO = B.NRO_PEDIDO AND A.TALON_PED = B.TALON_PED
				WHERE A.ESTADO IN (2, 3, 4) AND A.FECHA_PEDI >= GETDATE()-15 AND A.COD_CLIENT = 'GTWEB' AND COD_SUCURS = '01' AND A.TALON_PED = 96 GROUP BY B.COD_ARTICU)C
			ON A.COD_ARTICU = C.COD_ARTICU
			WHERE CANT_STOCK > 0
			AND A.COD_ARTICU NOT IN
				(SELECT COD_ARTICU FROM MAESTRO_DESTINOS WHERE (DESTINO LIKE '%DISCONTINUO%' OR DESTINO LIKE '%GUARDAR%') )
		)A
		LEFT JOIN (SELECT codigo COD_ARTICU, sum(SumaDeCantidad) CANT_TRANSF FROM Tr_X_Art WHERE Depo_Origen_Nombre LIKE '01-Central' group by codigo) B
		ON A.COD_ARTICU COLLATE Latin1_General_BIN = B.COD_ARTICU COLLATE Latin1_General_BIN
				
		)A
		
		)A
		
		WHERE CANT_STOCK > 0
		--ARTICULOS EXCLUIDOS DE LA APP DISTRIBUCION INICIAL
		AND COD_ARTICU NOT IN
		(SELECT COD_ARTICU FROM SJ_EXCLUIDOS WHERE CAST(GETDATE()AS DATE) BETWEEN FECHA AND CAST(DATEADD(DAY, 5, GETDATE()) AS DATE) GROUP BY COD_ARTICU )

		)A

		LEFT JOIN (SELECT COD_ARTICU, CANT_SUG FROM SOF_ECOMMERCE_ABASTECIMIENTO )B
		ON A.COD_ARTICU = B.COD_ARTICU
		
		ORDER BY 3, 1
		
		";

		$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

		?>


		<form method="POST" action="cargarPedidoNuevo.php" onkeypress = "return pulsar(event)">

		
		<div style="width:100%">
  
		  <table class="table table-striped table-fh table-12c" id="id_tabla">
			
		<thead>
			<tr style="font-size:smaller">
                <th style="width: 8%">FOTO</th>
                <th style="width: 10%">CODIGO</th>
                <th style="width: 1%"></th >
                <th style="width: 25%">DESCRIPCION</th>
                <th style="width: 12%">RUBRO</th>
                <th style="width: 1%"></th >
                <th style="width: 1%"></th >
                <th style="width: 10%">STOCK<br>CENTRAL</th>
                <th style='width: 10%'>STOCK<br>LOCAL</th>
                <th style='width: 10%'>VENTAS<br>30 DIAS</th>
                <th style="width: 5%">DIST</th>
                <th style="width: 5%">PEDIDO</th>
                <th style="width: 3%"><input type="submit" value="Enviar" class="btn btn-primary btn-sm"></th>
			</tr>
		</thead>
		
		<tbody>
		<?php


		while($v=odbc_fetch_array($result)){

			?>

			<div>
			
			
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
			<a target="_blank" href="../imagenes/<?php echo substr($v['COD_ARTICU'], 0, 13) ;?>.jpg"><img src="../imagenes/<?php echo substr($v['COD_ARTICU'], 0, 13) ;?>.jpg" alt="" height="50" width="50"></a>
			</td>
			
			
			<td style="width: 12%"> <?php echo $v['COD_ARTICU'] ;?></td>
			
			<td style="width: 1%"><input name="codArt[]" value="<?php echo $v['COD_ARTICU'] ;?>"  hidden></td>

			<td style="width: 25%"><?php echo $v['DESCRIPCIO'] ;?></td>
			
			<td style="width: 12%"><small><?php echo $v['RUBRO'] ;?></small></td>

			<td style="width: 1%"><input name="rubro[]" value="<?php echo $v['RUBRO'] ;?>"  hidden></td>
			
			<td style="width: 1%"><input name="stock[]" value="<?php echo $v['CANT_STOCK'] ;?>"  hidden></td>
			
			<td style="width: 10%" id="stock"><?php echo (int)($v['CANT_STOCK']) ;?></td>

            <td style="width: 10%"><?php echo (int)($v['STOCK_LOCAL']) ;?></td>

            <td style="width: 10%"><?php echo (int)($v['VENDIDO_LOCAL']) ;?></td>
			
			<td style="width: 5%"><?php echo (int)($v['DISTRI']) ;?></td>

			<td style="width: 5%"><input type="text" name="cantPed[]" value="<?php echo (int)($v['CANT_SUG']) ;?>" size="4" tabindex="1" id="articulo" onChange="total();verifica()"></td>

			<td style="width: 3%"></td>
			
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

		
		<div class="mt-2 text-center fixed fixed-bottom bg-white" style="height: 30px!important; background-color: white;">
			<a> <strong>Total de articulos:</strong> </a> <input name="total_todo" size="3" id="total" value="0" type="text">
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
		</html>

		<?php
	}

	else{
		//echo $_SESSION['nuevoPedido'].' '.$_SESSION['cargaPedido'];
		header("Location:../login.php");
	}
	
}
?>
