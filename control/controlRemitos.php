<?php
session_start();
if (!isset($_SESSION['username'])) {
	header("Location:../login.php");
} else {

	$permiso = $_SESSION['permisos'];
	$user = $_SESSION['codClient'];
	$rem = $_SESSION['rem'];

	require_once __DIR__.'/../class/remito.php';

	$remito = new Remito();
	$verificacion = $remito->verificacion($user);

?>
	<!DOCTYPE HTML>

	<html>

	<head>
		<title>Control Remitos</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<!-- Font Awesome -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="css/style.css">
		<?php require_once __DIR__.'/../assets/css/header.php';?>

	</head>

	<body>


		<div style="margin: 5px">
			<button onClick="window.location.href= 'index.php'" class="btn btn-danger mb-1">Cancelar</button>
			<h6 align="center">Remito: <a class="text-secondary"><?= $rem ?></a> 
				<a>Ultimo escaneado:</a>
				<a class="text-secondary">
					<a id="lastCodigoControlado"></a>
				</a>
			</h6>
		</div>

		<form id="formControlRemito">
			<div class="col- row" style="display: flex; justify-content: center;">
				<input class="form-control form-control-sm ml-2" type="text" name="codigo" id="codigoName" placeholder="Ingrese codigo..." style="width:200px;" autofocus></input>
				<input type="submit" value="Ingresar" class="btn btn-primary ml-2" style="margin-top: -5px;">
			</div>
		</form>

		<audio src="Wrong.ogg"></audio>

	<div class="container table-responsive" id="bodyControl" style="display: none;">
		<table class="table table-striped mt-2" id="tablaControl">
			<thead class="thead-dark">
				<tr>
					<td class="col-" style="width:3.5em">CODIGO</td>
					<td class="col-" style="width:15em; padding-left:0; padding-right:0;">DESCRIPCION</td>
					<td class="col-" style="width:2em">CANTIDAD</td>	
					<td class="col-" style="width:5em"></td>
				</tr>
			</thead>
			<tbody id="table">

			</tbody>

		</table>
	</div>
		<div class="col- text-center bg-white">
			<a style="text-align: left; margin-right:0.5em; font-size: 0.8em"> <strong>Ultimo:</strong> <a id="lastCodigoControlado" style="font-size: 0.8em"></a> <button id="buttonHistorial" type="button" class="btn btn-info btn-sm mr-3" >Ver</button></a>
			<a style="margin-right:0.5em; font-size: 0.8em"> <strong>Articulos:</strong></a> <a id="totalArt"></a>
			<button class="btn btn-success mt-2" id="btnProcesar">Procesar</button>
		</div>

	

	<script src="js/main.js"></script>
	<script>
		let remito = '<?= $rem ?>';
		let codClient = '<?= $user ?>';
	</script>
	
	</body>
	
	</html>

<?php

}

?>
