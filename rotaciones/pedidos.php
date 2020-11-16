<?php 

session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{



$_SESSION['codClient'] = $_GET['cliente'];






$permiso = $_SESSION['permisos'];
$local = $_SESSION['descLocal'];


?>
<!DOCTYPE HTML>
<html charset="UTF-8">

<head>

<meta charset="utf-8">
<title>INICIO</title>	

<link rel="shortcut icon" href="icono.jpg" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

</head>
<body>	

<button type="button" class="btn btn-primary" OnClick="location.href='/sistemas/login.php' " style="margin:5px">Cerrar Sesion</button>
<button type="button" class="btn btn-warning" OnClick="location.href='clientes.php' " style="margin:5px">Elegir Otro Cliente</button>
<h1 align="center">Elija la opción</h1>
<h3 align="center">Cliente <?php echo $_SESSION['codClient']; ?></h1>


<div align="center" style="margin-top:10px"> 
</br>
<img src="logo.jpg">
</div>

</br>

<ul class="nav justify-content-center">
	<div class="btn-group" role="group" aria-label="Basic example">
		<li class="list-group-item">Pedidos</li>
		<button type="button" class="btn btn-secondary" onclick="location.href='pedidos/general.php'">Nuevo Pedido</button>
		<button type="button" class="btn btn-secondary" onclick="location.href='pedidos/historial.php'">Historial</button>
	</div>
</ul>
</br>




</ul>

</body>
<?php
}
?>

