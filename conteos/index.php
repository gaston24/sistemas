<?php session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
?>
<!DOCTYPE HTML>

<html>
<head>

	<title>Historial</title>	
	<?php include __DIR__.'../../assets/css/header.php'; ?>
</head>
	<body>	

<button type="button" class="btn btn-primary" OnClick="location.href='/sistemas/login.php' " style="margin:5px">Cerrar Sesion</button>
<h1 align="center">Bienvenido/a <?= $_SESSION['descLocal'] ?></h1>


<div align="center" style="margin-top:10px"> 
<img src="../../css/logo.jpg">
</div>

<ul class="nav justify-content-center">
	<div class="btn-group" role="group" aria-label="Basic example">
		<li class="list-group-item">Pedidos</li>
		<button type="button" class="btn btn-secondary" onclick="location.href=''">Conteos</button>
		<button type="button" class="btn btn-secondary" onclick="location.href='../control/control_auditoria.php'">Remitos</button>
	</div>
</ul>
</br>


</body>
	
<?php
}
?>