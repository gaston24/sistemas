<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:login.php");

}else{
	
		?>
		<!doctype html>
		<html>
		<head>
		<meta charset="utf-8">
		<title>Detalle PPP</title>
		<link rel="shortcut icon" href="../icono.jpg" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		
		
		
		
		
		</head>
		<body>

		
		<div >
		<?php

		$dsn = "1 - CENTRAL";
		$user = "sa";
		$pass = "Axoft1988";
		$suc = $_SESSION['numsuc'];
		
		$_SESSION['tipo_pedido'] = 'GENERAL';
		$_SESSION['depo'] = '01';
		
		$codClient = $_SESSION['username'];
		
		$cliente = $_GET['cliente'];
		
		$cid = odbc_connect($dsn, $user, $pass);


		$sql="
		SET DATEFORMAT YMD

		SELECT CAST(FECHA_RECIBO AS date)FECHA, T_COMP, N_COMP, CAST(CAST(IMPORTE_RECIBO AS float) AS INT) IMPORTE_RECIBO , CAST(CAST(IMPORTE_IMPUTADO AS FLOAT) AS INT) IMPORTE_IMPUTADO, 
		CAST(PPP AS INT) PPP, CAST(DIAS AS INT) DIAS 
		FROM GC_VIEW_PPP
		WHERE COD_CLIENTE IN
		(
		SELECT COD_CLIENT FROM GVA14 WHERE COD_VENDED = 'Z4' AND FECHA_INHA = '1800-01-01'
		)
		AND COD_CLIENTE = '$cliente'
		AND FECHA_RECIBO >= GETDATE()-365
		
		ORDER BY 1 desc, 3
		
		";

		$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

		?>


		
		<div class="container">
		<table class="table table-striped" >
		
		<tr> 
		<strong>

			<td > <strong> FECHA </strong> </td>
		
			<td > <strong> TIPO COMP </strong> </td>

			<td > <strong> COMPROBANTE </strong> </td>
			
			<td > <strong> IMPORTE RECIBO </strong> </td>

			<td > <strong> IMPORTE IMPUTADO </strong> </td>
			
			<td > <strong> PPP </strong> </td>

		
		</strong>
		</tr>
		
		<?php

		while($v=odbc_fetch_array($result)){

			?>

			<div >
			
				
				<td align="left"> <?php echo $v['FECHA'] ;?> </td>
				
				<td align="left"> <?php echo $v['T_COMP'] ;?> </td>
				
				<td align="left"><a href="pppDetalleRec.php?recibo=<?php echo $v['N_COMP'] ; ?>"</a> <?php echo $v['N_COMP'] ;?> </td>

				<td align="left"> <?php echo $v['IMPORTE_RECIBO'] ;?> </td>
				
				<td align="left"> <?php echo $v['IMPORTE_IMPUTADO'] ;?> </td>
				
				<td align="left"> <?php echo $v['PPP'] ;?> </td>

				
				
				</tr>
				
			</div>

			<?php

		}

		?>



		</table>
		
		</div>

		</div>
		
		
				
		</body>
		</html>

		<?php

	
}
?>

