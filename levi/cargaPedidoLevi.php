<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:login.php");

}else{

echo '<h3 align="center">Aguarde un momento por favor</h3>';

for($i=0;$i<count($_POST['suc']);$i++){
	$suc = $_POST['suc'][$i];
	$dsn = $_POST['dsn'][$i];
	
	$selec = $_POST['selec'][$i];
	
	
	if($selec == 'si'){
	
		
	
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
	GROUP BY A.COD_ARTICU, A.CANT_STOCK, B.VENDIDO
	)A
	";

	$cid = @odbc_connect($dsn, $user, $pass);

	ini_set('max_execution_time', 300);
	$result1 = @odbc_exec($cid, $sql1)or die(exit("</br></br><H2 ALIGN='CENTER'>IMPOSIBLE CONECTARSE CON ".$dsn."</H2></br></br><H2 ALIGN='CENTER'>VUELVA PARA ATRAS Y DESTILDE LA CONEXION DE ESTE LOCAL</H2>"));

	while($v=odbc_fetch_array($result1)){
	//echo 'Codigo: '.$v['COD_ARTICU'].' - Stock:'.$v['CANT_STOCK'].'</br>';
		$dsn_cen = '1 - CENTRAL';
		$user_cen = 'sa';
		$pass_cen = 'Axoft1988';

		$codArticu = $v['COD_ARTICU'];
		$cantStock = $v['CANT_STOCK'];
		$cantVend  = $v['VENDIDO'];

		$sql2=
		"
		INSERT INTO SOF_PEDIDOS_CARGA_LEVI (NUM_SUC, COD_ARTICU, CANT_STOCK, VENDIDO) VALUES ($suc, '$codArticu', $cantStock, $cantVend);
		"
		;
		
		$cid2 = odbc_connect($dsn_cen, $user_cen, $pass_cen);
		
		ini_set('max_execution_time', 300);
		odbc_exec($cid2, $sql2)or die(exit("</br></br>IMPOSIBLE CONECTARSE CON ".$dsn));

	}
	
	
	
	
	
	
	}

}

}
?>
<script>setTimeout(function () {window.location.href= 'index.php';},1000);</script>