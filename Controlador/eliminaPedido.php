<?php
	session_start(); 
	ob_start(); 
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
<?php
		require_once __DIR__."/../class/extralarge.php";
		$suc = $_SESSION['numsuc'];

		$xl = new Extralarge;

		if($xl->getEnv() == 'DEV'){
			header('Location: ../index.php');
			die();
		}
		$xl->deletePedidos($suc);
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
<?php
	header('Location: cargaPedido.php');
	ob_end_flush(); 
?>