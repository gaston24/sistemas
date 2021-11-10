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
		<?php include '../../css/header_simple.php'; ?>

	</head>

	<body>


		<div style="margin: 5px">
			<button onClick="window.location.href= 'index.php'" class="btn btn-primary">Cancelar</button>
			<h3 align="center">Remito: <?= $rem ?> 
				<?php 
					if (isset($_POST['codigo'])) {
						echo ' - Ultimo codigo: ' . $_POST['codigo'];
					} 
				?>

		</div>

		<form action="" style="margin:20px" align="center" method="POST">
			<label>Leer Codigo</label>
			<input type="text" name="codigo" placeholder="Ingrese codigo" autofocus></input>
			<input type="submit" value="Ingresar" class="btn btn-primary">
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
			<div class="container">

				<table class="table table-striped" style="margin-left:50px; margin-right:50px" id="tabla">
					<tr>
						<td class="col-" style="width:20%"><h4>CODIGO</h4></td>
						<td class="col-" style="width:50%"><h4>DESCRIPCION</h4></td>
						<td class="col-" style="width:2%"><h4>CANTIDAD</h4></td>
						<td class="col-"></td>
					</tr>

					<?php
					$total = 0;
					foreach ($codigosControlados as $key => $value) {
						?>

							<tr class="fila-base">
								<td class="col-" style="width:20%"><?=$value[0]->COD_ARTICU; ?></td>
								<td class="col-" style="width:50%"><?=$value[0]->DESCRIPCIO; ?></td>
								<td class="col-" style="width:2%" align="center"><?=$value[0]->CANT_CONTROL; ?></td>
								<td class="col-"><img src="eliminar.png" width="23px" height="23px" align="left" onClick="window.location.href='eliminar_articulo.php?codigo=<?=$value[0]->COD_ARTICU; ?>'"></img></td>
							</tr>

						<?php
							$total += $value[0]->CANT_CONTROL;
					}
					?>

				</table>


				<div class="text-center fixed fixed-bottom bg-white" style="height: 30px!important; background-color: white; margin-bottom: 5px">
					<a style="text-align: left; margin-right:10px"> <strong>Ultimo controlado:</strong> <a id="lastCodigoControlado" style="text-align: left; margin-right:50px"></a> <button id="buttonHistorial" type="button" class="btn btn-info btn-sm mr-3" >Ver</button></a>
					<a style="display:none" id="valueGetData"></a>
					<a style="margin-right:15px"> <strong>Total de articulos:</strong> <?= $total; ?></a>
					<button onClick="window.location.href= 'controlador/procesar.php'" class="btn btn-primary btn-sm">Procesar</button>
				</div>

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