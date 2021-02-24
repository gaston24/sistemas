<?php 

session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{

$permiso = $_SESSION['permisos'];
$codClient = $_SESSION['codClient'];
$local = $_SESSION['descLocal'];

?>
<!DOCTYPE HTML>
<html charset="UTF-8">

<head>

<meta charset="utf-8">
<title>INICIO</title>	

<link rel="shortcut icon" href="../Controlador/icono.jpg" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

</head>
<body>	

<button type="button" class="btn btn-primary" OnClick="location.href='/sistemas/login.php' " style="margin:5px">Cerrar Sesion</button>

<div align="center" style="margin-top:10px"> 

<img src="../Controlador/logo.jpg">
</div>

<ul class="nav justify-content-center">
	<div class="btn-group" role="group" aria-label="Basic example">
		<li class="list-group-item">Opciones</li>
		<button type="button" class="btn btn-secondary" onclick="location.href='stockSuc.php'">Stock Locales</button>
		<button type="button" class="btn btn-secondary" onclick="location.href='informes.php'">Informes</button>
	</div>
</ul>
</br>
<ul class="nav justify-content-center">
	<div class="btn-group" role="group" aria-label="Basic example">
		<li class="list-group-item">Power BI</li>
		<button type="button" class="btn btn-secondary"  onclick="window.open('https://app.powerbi.com/view?r=eyJrIjoiZTA5MTc4ZjgtMWY4YS00NGEwLWE3ZDktYTU5Nzg3Y2E0ZDkzIiwidCI6IjQ0Y2E2MmNkLTY4MjItNDZkNC05NTUxLTEzNDQ5N2ZmM2VjMiIsImMiOjR9', '_blank')">Venta General</button>
		<button type="button" class="btn btn-secondary" onclick="window.open('https://app.powerbi.com/view?r=eyJrIjoiZThmZTVhNDAtMTc3My00YjY0LWIyNWItMDM0NWNlNmMzMmRhIiwidCI6IjQ0Y2E2MmNkLTY4MjItNDZkNC05NTUxLTEzNDQ5N2ZmM2VjMiIsImMiOjR9', '_blank')">Detalle Por Rubro</button>
	</div>
</ul>

</body>
<?php
}
?>

