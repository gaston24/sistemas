<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../../login.php");

}else{
	
		?>
		<!doctype html>
		<html>
		<head>
		<title>Carga de Pedidos</title>
		<?php include '../../../css/header.php'; ?>
		</head>
		<body>

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
		
		EXEC SJ_TIPO_PEDIDO_CORDOBA_1
		
		";

		$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

		?>

<!-- 
		<form method="POST" action="cargarPedidoNuevoCordoba.php" onkeypress = "return pulsar(event)"> -->
		<form id='formulario' method="POST">
		<div style="width:100%">
		  
		<table class="table table-striped table-fh table-12c" id="id_tabla">
		
		<thead>
			
			<?php include 'encabezado.php'; ?>
			
		</thead>
		
		<tbody>
		<?php


		while($v=odbc_fetch_array($result)){

			include 'tabla.php';

		}

		?>

		</tbody>

		
		
		</table>
		</div>
		
		</form>

		<div class="mt-2 text-center fixed fixed-bottom bg-white" style="height: 30px!important; background-color: white;" id="pantalla">

			<a> <strong>Total de articulos:</strong> </a> <input name="total_todo" size="3" id="total" value="0" type="text">

			<a> <strong>Importe total:</strong> </a> <input name="total_precio" size="3" id="totalPrecio" value="0" type="text" onChange="verificarCredito()">
			<a id="cupoCreditoExcedido"></a>
			
			
		</div>

		
		
		
	</body>

	<script src="js/main.js"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	</html>

		<?php

	
}
?>

