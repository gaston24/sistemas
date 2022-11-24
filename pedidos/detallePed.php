<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
	
$permiso = $_SESSION['permisos'];
?>
<!DOCTYPE HTML>

<html>
<head>
	<meta charset="utf-8">
	<title>DETALLE REMITOS</title>	
	<link rel="shortcut icon" href="XL.png" />
	<link rel="stylesheet" href="nuevoData/data.css" >

<body>	

<?php
require_once __DIR__.'/../class/pedido.php';
$suc = $_GET['suc'];
$nroPedido = $_GET['pedido'];
$tipo = $_GET['tipo'];

$pedido = new Pedido();
$pedidos = $pedido->traerDetallePedido($nroPedido, $suc);

?>

<div class="container">
	<div class="row mb-2">
		<div class="col-2"><a href="historial.php"><img src="imagenes/botonAtras.png"></a> </div>
		<div class="col-10"><?= '<h3>Pedido: '.$nroPedido.' - '.$tipo.'</h3>' ?></div>
	</div>
	
	
	
	
<table class="table table-striped" id="table">

<thead>
        <tr >
			<td><h4>FECHA</h4></td>
			<td><h4>CODIGO</h4></td>
			<td><h4>DESCRIPCION</h4></td>
			<td><h4>CANT</h4></td>  
        </tr>
</thead>
<tbody>
		
        <?php
		foreach ($pedidos as $v) {
			$fecha = $v['FECHA']->format('d/m/Y'); 

		?>
	
        <tr style="font-size:smaller; height: 5px">
			<td style="height: 5px"><?= $fecha ;?></a></td>
			<td style="height: 5px"><?= $v['COD_ARTICU'] ;?></a></td>
			<td style="height: 5px"><?= $v['DESCRIPCIO'] ;?></a></td>
			<td style="height: 5px"><?= (int) ($v['CANT']) ;?></a></td>
        </tr>
		
        <?php
			}
		?>

</tbody>
</table>

</div>
</body>

<script type="text/javascript" src="nuevoData/data.js"></script>
<script type="text/javascript" src="nuevoData/main.js"></script>

<?php
}
?>