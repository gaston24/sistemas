<?php session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
?>
<!DOCTYPE HTML>

<html>
<head>
	<meta charset="utf-8">
	<title>Historial</title>	
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<body>	





<div style="margin: 5px">
<button onClick="volver()" class="btn btn-primary">Inicio</button>
</div>

<script>
	function volver() {window.location.href= '../login.php';};
</script>

<div align="center" style="margin-top:5%">

<img src="../logo.jpg">

	<form action="ajustar.php" id="sucu" style="margin:20px" method="get">
		Elija sucursal:
		<select name="sucursal" form="sucu" >
			<option value="2 - NVOUNICENTER">Unicenter</option>
			<option value="3 - ALTO PALERMO">Alto Palermo</option>
			<option value="6 - AVELLANEDA">Avellaneda</option>
			<option value="7 - ABASTO">Abasto</option>
			<option value="8 - MORENO">Moreno</option>
			<option value="10 - SOLAR">Solar</option>
			<option value="11 - TORTUGAS">Tortugas</option>
			<option value="13 - CABILDO">Cabildo</option>
			<option value="29 - SIGLO">ROSARIO - Siglo</option>
			<option value="32 - MAR DEL PLATA">MDQ - Shopping</option>
			<option value="33 - MDQ-PASEO ALDREY">MDQ - Aldrey</option>
			<option value="38 - VILLA DEL PARQUE">Villa del Parque</option>
			<option value="40 - FLORES">Flores</option>
			<option value="48 - ALTO ROSARIO">ROSARIO - Alto Rosario</option>
			<option value="53 - CABALLITO">Caballito</option>
			<option value="54 - GALERIAS">Galerias</option>
			<option value="56 - GUEMES">MDQ - Guemes</option>
			<option value="60 - PORTAL">Portal</option>
			<option value="66 - DOT">DOT</option>
			<option value="70 - PALACE">ROSARIO - Palace</option>
			<option value="72 - GURRUCHAGA">Gurruchaga</option>
			<option value="75 - FLORES2">Flores 2</option>
			<option value="76 - SOLEIL">Soleil</option>
		</select>
	<input type="submit" value="Consultar" class="btn btn-primary btn-sm">
	</form>
	
<div style="margin: 5px">
<button onClick="volver()" class="btn btn-primary">Centralizado</button>
</div>

<script>
	function volver() {window.location.href= 'centralizado.php';};
</script>	
	
	
</div>	
<?php
}
?>