<?php session_start();
if (!isset($_SESSION['username'])) {
	header("Location:../login.php");
} else {

	if($_SESSION['connection_db'] == false){
		header('Location:../index.php');
	}

?>
<!doctype html>
<html>
<head>

<title>Control de Remitos</title>
<?php include __DIR__.'/../assets/css/header.php'; ?>
</head>
<body>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title>Control de Remitos</title>
	</head>

	<body>

		<button type="button" class="btn btn-primary" onclick="location.href='../index.php'" style="margin:5px">Inicio</button>
		<button type="button" class="btn btn-secondary" onclick="location.href='controlHistoricos.php'" style="margin:5px">Historial</button>


		<div align="center" style="margin-top:10%">
			<form action="limpiar.php" id="pedidos" method="get">

<br><br>
<div>
	
	<div style="display: inline-block">
		<label>Remito</label>
		<input type="text" name="rem" placeholder="Numero de Remito" class="form-control form-control-sm col-sm-13" style="width: 250px;" required autofocus >
	</div>
	
	
	
	<div class="form-group col-sm-3" style="display: inline-block">
      <label for="inputState">Usuario</label>
      <select id="inputState" class="form-control form-control-sm" name="usuario" style="width: 250px;" required>
        <option value="" selected >Usuario</option>
	<?php
	require_once __DIR__.'/../class/remito.php';
	$nroSucurs = $_SESSION['numsuc'];
	
	$data = new Remito();
	$usuarios = $data->listarUsuarios($nroSucurs);

	foreach ($usuarios as $v) {
		echo '<option value="'.$v['APELLIDO'].'_'.$v['NOMBRE'].'++'.$v['BLOQUE'].'">'.$v['APELLIDO'].' '.$v['NOMBRE'].'</option>';
	}
?>
	       
      </select>
    </div>
	
	
</div>
<input type="submit" value="CONSULTAR" class="btn btn-primary btn-sm"></br>
</form>
</div>


<?php
if($_SESSION['conteo'] == 1){
	echo '</br></br><div class="alert alert-danger" role="alert" style="margin-left:15%; margin-right:15%">	El remito no pertenece al local!</div>';
	$_SESSION['conteo'] = 0;
}elseif($_SESSION['conteo'] == 2){
	echo '</br></br><div class="alert alert-danger" role="alert" style="margin-left:15%; margin-right:15%">	El remito ya fue controlado</div>';
	$_SESSION['conteo'] = 0;
}elseif($_SESSION['conteo'] == 3){
	echo '</br></br><div class="alert alert-danger" role="alert" style="margin-left:15%; margin-right:15%">	El remito no fue ingresado en TANGO, debe realizar el ingreso antes de realizar el control</div>';
	$_SESSION['conteo'] = 0;
}


}
?>
</body>
</html>
