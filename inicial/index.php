<?php 
session_start(); 
if(!isset($_SESSION['username'])){
  
  header("Location:login.php");

}elseif($_SESSION['habPedidos']==0){
	
	header("Location:../index.php");  

}else{
	
$permiso = $_SESSION['permisos'];
?>
<!DOCTYPE HTML>

<html>
<head>

<meta charset="utf-8">
<title>INICIO</title>	

<link rel="shortcut icon" href="icono.jpg" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

</head>
<body>	

<div align="center" style="margin-top:50px"> 
<img src="logo.jpg">
</div>
</br>
<ul class="nav justify-content-center">
<div class="btn-group" role="group" aria-label="Basic example">
  <button type="button" class="btn btn-secondary" onclick="location.href='cargaNuevo.php'">Nueva Distribucion Inicial</button>
  <button type="button" class="btn btn-secondary" onclick="location.href='stock.php'">Revisar Pedidos</button>
</div>
</ul>
</br>



</body>
<?php
}
?>

