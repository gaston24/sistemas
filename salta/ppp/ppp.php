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
		<title>PPP</title>
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
		
		$cid = odbc_connect($dsn, $user, $pass);


		$sql="
		SET DATEFORMAT YMD

		SELECT COD_CLIENTE, RAZON_SOCIAL, CAST(PPP AS INT)PPP, CAST(CUPO_CREDI AS int) CUPO_CRED, 
		CAST(SALDO_CC AS INT)SALDO_CC, CAST(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END AS INT)CHEQUE, 
		CAST((SALDO_CC+(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END)) AS INT) TOTAL_DEUDA
		FROM
		(
		SELECT COD_CLIENTE, RAZON_SOCIAL, /*CAST(CAST(SUM(IMPORTE_RECIBO) AS float) AS INT) IMPORTE_RECIBO , CAST(CAST(SUM(IMPORTE_IMPUTADO) AS FLOAT) AS INT) IMPORTE_IMPUTADO, */
		CAST(AVG(PPP) AS decimal(10,2)) PPP, CAST(AVG(DIAS) AS INT) DIAS, B.CUPO_CREDI, B.SALDO_CC
		FROM GC_VIEW_PPP A
		INNER JOIN GVA14 B
		ON A.COD_CLIENTE = B.COD_CLIENT
		WHERE B.COD_VENDED = 'Z4' AND B.FECHA_INHA = '1800-01-01'
		AND FECHA_RECIBO >= GETDATE()-365
		GROUP BY COD_CLIENTE, RAZON_SOCIAL, B.CUPO_CREDI, B.SALDO_CC
		)A
		LEFT JOIN
		(SELECT CLIENTE, SUM(IMPORTE_CH)CHEQUES FROM SBA14 WHERE FECHA_CHEQ >= GETDATE() AND ESTADO NOT IN ('X', 'R') GROUP BY CLIENTE) B
		ON A.COD_CLIENTE = B.CLIENTE
		ORDER BY 1
		
		";
		
		
		ini_set('max_execution_time', 300);
		$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

		?>


		
		<div class="container">
		<table class="table table-striped" >
		
		<tr> 
		<strong>

			<td > <strong> COD CLIENTE </strong> </td>

			<td > <strong> RAZON SOCIAL </strong> </td>
			
			<td > <strong> PPP</strong> </td>

			<td > <strong> CUPO_CRED </strong> </td>
			
			<td > <strong> SALDO CC</strong> </td>
			
			<td > <strong> CHEQUE</strong> </td>
			
			<td > <strong> TOTAL DEUDA</strong> </td>
			
			
			
		
		
		</strong>
		</tr>
		
		<?php

		while($v=odbc_fetch_array($result)){

			?>

			<div >
			
				
				<td align="left"> <?php echo $v['COD_CLIENTE'] ;?> </td>
				
				<td align="left"><a href="pppDetalle.php?cliente=<?php echo $v['COD_CLIENTE'] ; ?>"</a> <?php echo $v['RAZON_SOCIAL'] ;?> </td>

				<td align="left"> <?php echo $v['PPP'] ;?> </td>
				
				<td align="left"> <?php echo number_format($v['CUPO_CRED'], 0, '', '.') ;?> </td>
				
				<td align="left"> <?php echo number_format($v['SALDO_CC'], 0, '', '.') ;?> </td>
				
				<td align="left"> <?php echo number_format($v['CHEQUE'], 0, '', '.') ;?> </td>
				
				<td align="left"> <?php echo number_format($v['TOTAL_DEUDA'], 0, '', '.') ;?> </td>
				
				
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

