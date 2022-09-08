<?php
session_start(); 
if(!isset($_SESSION['username'])){
	
	header("Location:login.php");
}else{
	
	if($_SESSION['nuevoPedido']==1 && $_SESSION['cargaPedido']==1){
	
	$dsn_cen = '1 - CENTRAL';
	$user_cen = 'sa';
	$pass_cen = 'Axoft1988';
	
	
	echo '<h3 align="center">Aguarde un momento por favor</h3>';
	
	$sql=
	"
	TRUNCATE TABLE SOF_PEDIDOS_CARGA_LOPEZ
	"
	;

	$cid = odbc_connect($dsn_cen, $user_cen, $pass_cen);

	odbc_exec($cid, $sql)or die(exit("Error en odbc_exec"));
	}
	else{
		header("Location:login.php");
	}
}
?>

<script>setTimeout(function () {window.location.href= 'eligeSucCordoba.php';},1000);</script>