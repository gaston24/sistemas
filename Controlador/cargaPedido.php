<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../login.php");

}else{

?>
<head>

<title>Pedidos</title>
<meta charset="UTF-8"></meta>
<link rel="shortcut icon" href="../css/icono.jpg" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

		</head>
<div class="container-fluid">
	<div class="d-flex justify-content-center mt-1">
		<div>
			<h2>Redireccionando</h2>
		</div>
	</div>	
		
<?php

	if($_SESSION['nuevoPedido']==1 && $_SESSION['cargaPedido']==1){

	$dsn = $_SESSION['dsn'];
	$suc = $_SESSION['numsuc'];
	$local = $_SESSION['descLocal'];
	$user = 'sa';
	$pass = 'Axoft1988';
	
	
	$sql1 = "
	SET DATEFORMAT YMD
	SELECT COD_ARTICU, CANT_STOCK, CASE WHEN VENDIDO IS NULL THEN 0 ELSE VENDIDO END VENDIDO  FROM
	(
	SELECT A.COD_ARTICU, A.CANT_STOCK, B.VENDIDO FROM STA19 A
	LEFT JOIN 
	(
	SELECT COD_ARTICU, SUM(CASE T_COMP WHEN 'NCR' THEN CANTIDAD*-1 ELSE CANTIDAD END)VENDIDO FROM GVA53 WHERE FECHA_MOV > (GETDATE()-30) GROUP BY COD_ARTICU
	)B
	ON A.COD_ARTICU = B.COD_ARTICU
	WHERE A.COD_ARTICU IN 
	(SELECT DISTINCT(COD_ARTICU) FROM GVA53 WHERE FECHA_MOV > (GETDATE()-180))
	OR A.COD_ARTICU IN 
	(
	SELECT COD_ARTICU FROM ( SELECT A.COD_ARTICU, A.CANT_STOCK, B.VENDIDO FROM STA19 A
	LEFT JOIN (SELECT COD_ARTICU, SUM(CASE T_COMP WHEN 'NCR' THEN CANTIDAD*-1 ELSE CANTIDAD END)VENDIDO FROM GVA53 WHERE FECHA_MOV > (GETDATE()-30) GROUP BY COD_ARTICU)B
	ON A.COD_ARTICU = B.COD_ARTICU )A WHERE CANT_STOCK > 0 AND VENDIDO IS NULL
	)
	AND COD_DEPOSI != 'OU'
	GROUP BY A.COD_ARTICU, A.CANT_STOCK, B.VENDIDO
	)A
	";

	$cid = @odbc_connect($dsn, $user, $pass)or die(exit("</br></br><H2 ALIGN='CENTER'>IMPOSIBLE CONECTARSE CON ".$local."</H2></br></br><H2 ALIGN='CENTER'>Chequee la conexion de internet</H2>"));

	ini_set('max_execution_time', 300);
	$result1 = odbc_exec($cid, $sql1)or die(exit("</br></br><H2 ALIGN='CENTER'>IMPOSIBLE CONECTARSE CON ".$local."</H2></br></br><H2 ALIGN='CENTER'>Chequee la conexion de internet</H2>"));

	while($v=odbc_fetch_array($result1)){
	//echo 'Codigo: '.$v['COD_ARTICU'].' - Stock:'.$v['CANT_STOCK'].'</br>';
		$dsn_cen = '1 - CENTRAL';
		$user_cen = 'Axoft';
		$pass_cen = 'Axoft';

		$codArticu = $v['COD_ARTICU'];
		$cantStock = $v['CANT_STOCK'];
		$cantVend  = $v['VENDIDO'];

		$sql2=
		"
		INSERT INTO SOF_PEDIDOS_CARGA (NUM_SUC, COD_ARTICU, CANT_STOCK, VENDIDO) VALUES ($suc, '$codArticu', $cantStock, $cantVend);
		"
		;
		
		$cid2 = odbc_connect($dsn_cen, $user_cen, $pass_cen);
		
		ini_set('max_execution_time', 300);
		odbc_exec($cid2, $sql2)or die(exit("Error en odbc_exec"));

	}
	
	$_SESSION['nuevoPedido']=0;
	
	header("Location:../index.php");
	
	}
	
	else{
		header("Location:../login.php");
	}
}
?>

</div>




