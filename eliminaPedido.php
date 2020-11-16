<?php
session_start(); 
if(!isset($_SESSION['username'])){
	
	header("Location:login.php");
}else{
	
	?>
		<head>

<title>Pedidos</title>
<meta charset="UTF-8"></meta>
<link rel="shortcut icon" href="../css/icono.jpg" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

		</head>
	<?php
	
	if($_SESSION['nuevoPedido']==1 && $_SESSION['cargaPedido']==1){
	
	$dsn_cen = '1 - CENTRAL';
	$user_cen = 'Axoft';
	$pass_cen = 'Axoft';
	
	$suc = $_SESSION['numsuc'];
	
	
	
	$sql=
	"
	DELETE FROM SOF_PEDIDOS_CARGA WHERE NUM_SUC = $suc;
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
<div class="container-fluid">
	<div class="d-flex justify-content-center mt-1">
		<div>
			<h2>Aguarde un momento por favor</h2>
		</div>
	</div>	
	<div class="d-flex justify-content-center mt-1">	
		<div class="mt-2">
			<h3>Conectando con el local</h3>
		</div>
	</div>	
	<div class="d-flex justify-content-center mt-1">
		<img src="cargando.gif" >
	</div>
	
</div>	
<script>setTimeout(function () {window.location.href= 'cargaPedido.php';},1);</script>
<?php
header('Location: cargaPedido.php');
?>