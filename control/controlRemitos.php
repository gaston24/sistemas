<?php
session_start();
if (!isset($_SESSION['username'])) {
	header("Location:../login.php");
} else {

	$permiso = $_SESSION['permisos'];
	$user = $_SESSION['codClient'];
	$rem = $_SESSION['rem'];

	require_once 'class/control.php';

	$control = new Remito();

	$verificacion = $control->verificacion($user);


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
		<?php include '../../css/header_simple.php'; ?>

	</head>

	<body>


		<div style="margin: 5px">
			<button onClick="window.location.href= 'index.php'" class="btn btn-danger mb-1">Cancelar</button>
			<h4 align="center">Remito: <a class="text-secondary"><?= $rem ?></a> 
				<a>Ultimo escaneado:</a>
				<a class="text-secondary">
					<?php 
						if (isset($_POST['codigo'])) {
							echo $_POST['codigo'];
						} 
					?>
				</a>
			</h4>
		</div>

		<form action="" style="margin-top:1rem" align="center" method="POST">
			<div class="col- row" style="display: flex; justify-content: center;">
				<label>Escaneo</label>
				<input class="form-control form-control-sm ml-2" type="text" name="codigo" placeholder="Ingrese codigo..." style="width:200px;" autofocus></input>
				<input type="submit" value="Ingresar" class="btn btn-primary ml-2" style="margin-top: -5px;">
			</div>
		</form>

		<?php

		if (isset($_POST['codigo']) || $verificacion != 0) {

			$user_local = $_SESSION['usuario'];

			if (isset($_POST['codigo'])) {

				$codigo = str_replace("'", "-", $_POST['codigo']);
				$codigo = str_replace(" ", "", $_POST['codigo']);
				$codigo = strtoupper($codigo);


				$existe = $control->buscarSinonimo($codigo);
				

				if(count($existe) != 0){
					$control->insertarControlLocal($user, $rem, $codigo, $user_local);
				}

				$wrongCode = '';
				$wrongCode = $control->wrongCode($codigo);
				echo $wrongCode;

			$codigosControlados = $control->traerControladoTemporal($user);

			?>
			<div class="container table-responsive">
				<table class="table table-striped mt-2" id="tabla">
					<thead class="thead-dark">
						<tr>
							<td class="col-" style="width:4em">CODIGO</td>
							<td class="col-" style="width:10em">DESCRIPCION</td>
							<td class="col-" style="width:5em">CANTIDAD</td>	
							<td class="col-" style="width:5em"></td>
						</tr>
					</thead>
					<tbody id="table">
					<?php
					$total = 0;
					foreach ($codigosControlados as $key => $value) {
						?>
	
							<tr class="fila-base">
								<td class="col-" style="width:6rem"><?=$value[0]->COD_ARTICU; ?></td>
								<td class="col-" style="width:10rem"><?=$value[0]->DESCRIPCIO; ?></td>
								<td class="col-" style="width:3rem" align="center"><?=$value[0]->CANT_CONTROL; ?></td>
								<td class="col-"><img src="eliminar.png" width="23px" height="23px" align="left" onClick="window.location.href='eliminar_articulo.php?codigo=<?=$value[0]->COD_ARTICU; ?>'"></img></td>
							</tr>

						<?php
							$total += $value[0]->CANT_CONTROL;
					}
					?>
					</tbody>

				</table>
			</div>

			<div class="col- text-center bg-white">
					<a style="text-align: left; margin-right:0.5em; font-size: 0.9em"> <strong>Ultimo:</strong> <a id="lastCodigoControlado" style="font-size: 0.9em"></a> <button id="buttonHistorial" type="button" class="btn btn-info btn-sm mr-3" >Ver</button></a>
					
					<a style="margin-right:0.5em; font-size: 0.9em"> <strong>Total articulos:</strong> <?= $total; ?></a>
					<button onClick="window.location.href= 'controlador/procesar.php'" class="btn btn-success mt-2">Procesar</button>
			</div>

			<?php
		}
	}
}
	?>
	<script>
		let user = '<?=$user?>'
	</script>

	<script src="js/axios.js"></script>
	<script src="js/historial.js"></script>

</body>

</html>