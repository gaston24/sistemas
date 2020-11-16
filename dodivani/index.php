<?php 

session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{


$local = $_SESSION['descLocal'];






?>
<!DOCTYPE HTML>
<html charset="UTF-8">

<head>



<?php include '../../css/header_simple.php'; ?>

<title>INICIO</title>

</head>
<body>	

<button type="button" class="btn btn-primary" OnClick="location.href='/sistemas/login.php' " style="margin:5px">Cerrar Sesion</button>
<h1 align="center">Bienvenido Dodivani Dona srl</h1>


<div align="center" style="margin-top:10px"> 

<img src="logo.jpg">
</div>

<ul class="nav justify-content-center">
	<div class="btn-group" role="group" aria-label="Basic example">
		<li class="list-group-item">Pedidos</li>
		<button type="button" class="btn btn-secondary" onclick="location.href='pedidos/general.php'">Generales</button>
		<button type="button" class="btn btn-secondary" onclick="location.href='pedidos/accesorios.php'">Accesorios</button>
		<button type="button" class="btn btn-secondary" onclick="location.href='pedidos/historial.php'">Historial</button>
	</div>
</ul>
</br>


<ul class="nav justify-content-center">
	<div class="btn-group" role="group" aria-label="Basic example">
		<li class="list-group-item">Guias de despacho</li>
		
		<button type="button" class="btn btn-secondary" onclick="location.href='guia/index.php'">Detalle de Guias</button>
		
	</div>
</ul>
</br>
<ul class="nav justify-content-center">
	<div class="btn-group" role="group" aria-label="Basic example">
		<li class="list-group-item">Otros</li>
		<button type="button" class="btn btn-secondary" onclick="location.href='otros/stockLocales.php'">Stock Locales</button>
		<button type="button" class="btn btn-secondary" onclick="location.href='otros/trazabilidad.php'">Trazabilidad</button>
	</div>
</ul>
</br>



</br></br>








</ul>

</body>
<?php
}
?>

