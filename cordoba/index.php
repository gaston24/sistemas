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



<?php include '../assets/css/header_simple.php'; ?>

<title>INICIO</title>

</head>
<body>	

<button type="button" class="btn btn-primary" OnClick="location.href='/sistemas/login.php' " style="margin:5px">Cerrar Sesion</button>
<h1 align="center">Bienvenido Original Products 1966 srl</h1>


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
		<li class="list-group-item">Backup</li>
		<button type="button" class="btn btn-secondary" onclick="location.href='pedidos/backup_general.php'">Generales</button>
		<button type="button" class="btn btn-secondary" onclick="location.href='pedidos/backup_accesorios.php'">Accesorios</button>
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
		<li class="list-group-item">PPP</li>
		
		<button type="button" class="btn btn-secondary" onclick="location.href='ppp/ppp.php'">Detalle</button>
		<button type="button" class="btn btn-secondary" onclick="location.href='ppp/recibos.php'">Cobros Mayoristas</button>
		
	</div>
</ul>
</br>
<ul class="nav justify-content-center">
	<div class="btn-group" role="group" aria-label="Basic example">
		<li class="list-group-item">Estadisticas</li>
		
		<button type="button" class="btn btn-secondary" onclick="location.href='estadisticas/ventas.php'">Ventas Diarias</button>
		<button type="button" class="btn btn-secondary" onclick="location.href='estadisticas/index.php'">Indices</button>
		<button type="button" class="btn btn-secondary" onclick="window.open('https://app.powerbi.com/view?r=eyJrIjoiY2U5MDc4NzEtMTVkYy00YTNmLWJmNjYtMWRiZjBhZTM1OGI3IiwidCI6IjQ0Y2E2MmNkLTY4MjItNDZkNC05NTUxLTEzNDQ5N2ZmM2VjMiIsImMiOjR9', '_blank')">Ventas Mayoristas</button>
		
	</div>
</ul>
</br></br>








</ul>

</body>
<?php
}
?>

