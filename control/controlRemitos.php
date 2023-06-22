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
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
		<link rel="stylesheet" href="css/style.css">
		<?php require_once __DIR__.'/../assets/css/header.php';?>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


	</head>

	<body>


		<div>
		<div id="contBtn">
			<button onClick="window.location.href= 'index.php'" class="btn btn-danger mt-2">Cancelar <i class="bi bi-x-circle"></i></button>
			<button class="btn btn-secondary mt-2" id="btnBorrador" hidden>Guardar <i class="bi bi-card-list"></i></button>
			<button class="btn btn-dark mt-2" id="btnTraerBorrador" hidden>Traer <i class="bi bi-card-list"></i></button>
		</div>
			<h6 align="center">Remito: <a class="text-secondary" id="numRem"><?= $rem ?></a> 
				<!-- <a>Ultimo escaneado:</a>
				<a class="text-secondary">
					<a id="lastCodigoControlado"></a>
				</a> -->
			</h6>
		</div>

		<form id="formControlRemito">
			<div class="col- row" style="display: flex; justify-content: center;">
				<input class="form-control form-control-sm ml-2" type="text" name="codigo" id="codigoName" placeholder="Ingrese codigo..." style="width:200px;" autofocus></input>
				<button type="submit" class="btn btn-primary ml-2" style="margin-top: -5px;">Ingresar <i class="bi bi-plus-circle"></i></button>
			</div>
		</form>

		<audio src="Wrong.ogg"></audio>

	<div class="container table-responsive" id="bodyControl">
		<table class="table table-striped mt-2 ml-2">
			<thead class="thead-dark mt-1">
				<tr>
					<td class="col-" style="width:3.5em">CODIGO</td>
					<td class="col-" style="width:15em">DESCRIPCION</td>
					<td class="col-" style="width:2em">CANTIDAD</td>	
					<td class="col-" style="width:5em"></td>
				</tr>
			</thead>
			<tbody id="table">

			</tbody>
		
		</table>
	</div>
		<div class="col- text-center bg-white mt-1">
			<a style="text-align: left; margin-right:0.5em; font-size: 0.8em"> <strong>Ult.:</strong> <a id="lastCodigoControlado" style="font-size: 0.8em"></a> <button id="buttonHistorial" type="button" class="btn btn-info btn-sm mr-3" >Ver <i class="bi bi-search"></i></button></a>
			<a style="margin-right:0.3em; font-size: 0.8em"> <strong>Art.:</strong></a> <a id="totalArt"></a>
			<button class="btn btn-success btn-sm" id="btnProcesar">Procesar <i class="bi bi-check2-circle"></i></button>
		</div>

	
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
