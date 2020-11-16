<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../sistemas/login.php");

}else{
?>	
<!doctype html>
<html>
<head>
<?php include '../../css/header.php'; ?>
<title>PPP</title>
</head>
<body>


<div >
<?php

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";

$ven = $_GET['cod'];

$cid = odbc_connect($dsn, $user, $pass);


$sql="
SET DATEFORMAT YMD

SELECT COD_CLIENTE, GRUPO_EMPR, PLAZO, RAZON_SOCIAL, PPP, CUPO_CRED, SALDO_CC, CHEQUE, CHEQUES_10_DIAS, TOTAL_DEUDA, TOTAL_DISPONIBLE, 
CASE WHEN VENCIDAS < 0 THEN A_VENCER + VENCIDAS ELSE (ISNULL(A_VENCER, 0)) END A_VENCER,
ISNULL((CASE WHEN (ISNULL(VENCIDAS, 0)) < 0 THEN 0 ELSE VENCIDAS END), 0) VENCIDAS
FROM
(

	SELECT COD_CLIENTE, GRUPO_EMPR, CASE WHEN FECHA IS NULL THEN 'OK' ELSE 'NO' END PLAZO, RAZON_SOCIAL, PPP, CUPO_CRED, SALDO_CC, CHEQUE, CHEQUES_10_DIAS, TOTAL_DEUDA, 
	(CUPO_CRED - TOTAL_DEUDA) TOTAL_DISPONIBLE, A_VENCER, VENCIDAS
	FROM
	(
		SELECT COD_CLIENTE, D.FECHA, RAZON_SOCIAL, GRUPO_EMPR, CAST(PPP AS INT)PPP, CAST(CUPO_CREDI AS int) CUPO_CRED, 
		CAST(SALDO_CC AS INT)SALDO_CC, 
		CAST(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END AS INT)CHEQUE, 
		CAST(CASE WHEN C.CHEQUES_PRONTO IS NULL THEN 0 ELSE C.CHEQUES_PRONTO END AS INT)CHEQUES_10_DIAS, 
		CAST((SALDO_CC+(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END)) AS INT) TOTAL_DEUDA, SUM(A_VENCER)A_VENCER, SUM(A.VENCIDAS)VENCIDAS
		FROM
		(
			SELECT COD_CLIENTE, RAZON_SOCIAL, B.GRUPO_EMPR, C.NOMBRE_GRU , 
			CAST(AVG(PPP) AS decimal(10,2)) PPP, CAST(AVG(DIAS) AS INT) DIAS, B.CUPO_CREDI, B.SALDO_CC, D.A_VENCER, D.VENCIDAS
			FROM GC_VIEW_PPP A
			INNER JOIN GVA14 B
			ON A.COD_CLIENTE = B.COD_CLIENT
			LEFT JOIN GVA62 C
			ON B.GRUPO_EMPR = C.GRUPO_EMPR
			LEFT JOIN SJ_SALDOS_CC D
			ON A.COD_CLIENTE = D.COD_CLIENT
			WHERE B.COD_CLIENT LIKE 'MA%'
			AND FECHA_RECIBO >= GETDATE()-365
			AND B.COD_VENDED = '$ven'
			AND B.COD_CLIENT NOT IN (SELECT COD_CLIENT FROM SJ_PPP_EXCLUYE_CLIENTE WHERE SELEC = 1)
			GROUP BY COD_CLIENTE, RAZON_SOCIAL, B.GRUPO_EMPR, C.NOMBRE_GRU, B.CUPO_CREDI, B.SALDO_CC, D.A_VENCER, D.VENCIDAS
		)A
		LEFT JOIN
		(SELECT CLIENTE, SUM(IMPORTE_CH)CHEQUES FROM SBA14 WHERE FECHA_CHEQ >= GETDATE() AND ESTADO NOT IN ('X', 'R') GROUP BY CLIENTE) B
		ON A.COD_CLIENTE = B.CLIENTE
		LEFT JOIN
		(SELECT CLIENTE, SUM(IMPORTE_CH)CHEQUES_PRONTO FROM SBA14 WHERE (FECHA_CHEQ >= GETDATE() AND FECHA_CHEQ <= GETDATE()+10) AND ESTADO NOT IN ('X', 'R') GROUP BY CLIENTE) C
		ON A.COD_CLIENTE = C.CLIENTE
		LEFT JOIN
		(SELECT FECHA, COD_CLIENT FROM (SELECT MIN(FECHA_EMIS)FECHA, COD_CLIENT FROM GVA12 A WHERE FECHA_EMIS >= GETDATE()-45 
		AND COD_CLIENT LIKE 'MA%' AND A.T_COMP = 'FAC' AND ESTADO = 'PEN' GROUP BY COD_CLIENT )A WHERE FECHA < GETDATE()-30)D
		ON A.COD_CLIENTE = D.COD_CLIENT
		GROUP BY COD_CLIENTE, D.FECHA, RAZON_SOCIAL, GRUPO_EMPR, PPP, CUPO_CREDI, SALDO_CC, (CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END), 
		(CASE WHEN C.CHEQUES_PRONTO IS NULL THEN 0 ELSE C.CHEQUES_PRONTO END), ((SALDO_CC+(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END)))
	)A

)A



ORDER BY 1

";


ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>
<?php include '../agrega_clientes.php'?>
<div style="width:100%">
  
  <table class="table table-striped table-fh table-11c">
	
	<thead>
		<tr >
			<th style="width: 5%"><h6>CLIENTE</h6></th>
			<th style="width: 15%"><h6>RAZON SOCIAL</h6></th>
			<th style="width: 5%"><h6>PPP<br>12 meses</h6></th>
			<th style="width: 5%"><h6>CUPO<br>CRED</h6></th>
			<th style="width: 5%"><h6>SALDO<br>CC</h6></th>
			<th style="width: 5%"><h6>VENCIDAS</h6></th>
			<th style="width: 5%"><h6>A<br>VENCER</h6></th>
			<th style="width: 5%"><h6>TOTAL<br>CHEQUES</h6></th>
			<th style="width: 5%"><h6>CHEQUES<br>10 DIAS</h6></th>
			<th style="width: 5%"><h6>TOTAL<br>DEUDA</h6></th>
			<th style="width: 5%"><h6>DISPONIBLE</h6></th>
		</tr>
	</thead>

	<div >
	<tbody >
<?php

	$saldo_cc = 0;
	$vencidas = 0;
	$a_vencer = 0;
	$total_cheques = 0;
	$cheques_diez = 0;
	$total_deuda = 0;
	$disponible = 0;

 while($v=odbc_fetch_array($result)){

	?>
	<tr>

	<td style="width: 5%"> 
				<?php if ($v['PLAZO']=='NO'){ echo '<strong><a style="color:red;">'.$v['COD_CLIENTE'].'</a></strong>';} else{echo $v['COD_CLIENTE'];}?>  
			</td>
			
			<td style="width: 15%"><a href="pppDetalle.php?cliente=<?php echo $v['COD_CLIENTE'] ; ?>"</a> <?php echo $v['RAZON_SOCIAL'] ;?> </td>

			<td style="width: 5%"> <?php echo $v['PPP'] ;?> </td>
			
			<td style="width: 5%"> <?php echo number_format($v['CUPO_CRED'], 0, '', '.') ;?> </td>
			
			<td style="width: 5%"> <?php echo '<strong>'.number_format($v['SALDO_CC'], 0, '', '.').'</strong>' ;?> </td>
			
			<td style="width: 5%">
			<?php if ($v['PLAZO']=='NO'){ echo '<strong><a style="color:red;">'.number_format($v['VENCIDAS'], 0, '', '.').'</a></strong>';} else{echo number_format($v['VENCIDAS'], 0, '', '.');}?>  
			</td>
			
			<td style="width: 5%"> <?php echo number_format($v['A_VENCER'], 0, '', '.')?> </td>
			
			<td style="width: 5%"> <?php 
			if($v['CHEQUE']>0)
			{echo '<a href="pppCheque.php?cliente='.$v['COD_CLIENTE'].'"</a>';} 
			echo number_format($v['CHEQUE'], 0, '', '.') ;?> 
			</td>
			
			<td style="width: 5%">
				<?php if ($v['CHEQUES_10_DIAS']>0){ echo '<strong><a style="color:LimeGreen ;">'.number_format($v['CHEQUES_10_DIAS'], 0, '', '.').'</a></strong>';} else{echo number_format($v['CHEQUES_10_DIAS'], 0, '', '.');}?>  
			</td>
			
			<td style="width: 5%"> <?php echo '<strong>'.number_format($v['TOTAL_DEUDA'], 0, '', '.').'</strong>' ;?> </td>
		
			<td style="width: 5%"> <?php echo number_format($v['TOTAL_DISPONIBLE'], 0, '', '.') ;?> </td>

		</tr>

	<?php
	
	$saldo_cc += $v['SALDO_CC'];
	$vencidas += $v['VENCIDAS'];
	$a_vencer += $v['A_VENCER'];
	$total_cheques += $v['CHEQUE'];
	$cheques_diez += $v['CHEQUES_10_DIAS'];
	$total_deuda += $v['TOTAL_DEUDA'];
	$disponible += $v['TOTAL_DISPONIBLE'];
 }

?>
	<tr>
		<td style="width: 5%"> </td>
		<td style="width: 15%"><h5 align="center">TOTAL</h6></td>
		<td style="width: 5%"> </td>
		<td style="width: 5%"> </td>
		<td style="width: 5%"><h6><?php echo number_format($saldo_cc, 0, '', '.') ;?></h6></td>
		<td style="width: 5%"><h6><?php echo number_format($vencidas, 0, '', '.') ;?></h6></td>
		<td style="width: 5%"><h6><?php echo number_format($a_vencer, 0, '', '.') ;?></h6></td>
		<td style="width: 5%"><h6><?php echo number_format($total_cheques, 0, '', '.') ;?></h6></td>
		<td style="width: 5%"><h6><?php echo number_format($cheques_diez, 0, '', '.') ;?></h6></td>
		<td style="width: 5%"><h6><?php echo number_format($total_deuda, 0, '', '.') ;?></h6></td>
		<td style="width: 5%"><h6><?php echo number_format($disponible, 0, '', '.') ;?></h6></td>
		</tr>


</tbody>
</div>
</table>

</div>

</div>


		
</body>
</html>

		
<?php
}
?>
