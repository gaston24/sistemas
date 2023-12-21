<?php 
session_start(); 
if(!isset($_SESSION['username']) || ($_SESSION['usuarioUy'] == 1)){
	header("Location:login.php");
}else{
$permiso = $_SESSION['permisos'];
$suc = $_SESSION['codClient'];
$numSuc = $_SESSION['numsuc'];

require 'Class/Pedido.php';

$pedido = new Pedido();

?>

<!DOCTYPE HTML>
<html>

<head>
<link rel="icon" type="image/jpg" href="images/LOGO XL 2018.jpg">
	<title>Entregas de ecommerce</title>
<!-- Bootstrap CSS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<!-- Including Font Awesome CSS from CDN to show icons -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="css/style.css">
</head>

<body>	
	
	<title>Entregas Ecommerce</title>	
	<form action="procesar.php" id="sucu" method="post" style="margin-top:2rem">
	<div class="form-row mb-4" id="headerCheck">
		<button type="button" class="btn btn-primary" id="inicio" onClick="window.location.href='../index.php'">Inicio</button>
		<label style="margin-left:1%; margin-right:1%">Busqueda:</label>
		<input type="text" id="textBox"  placeholder="Busqueda rapida..." onkeyup="myFunction()"  class="form-control form-control-sm col-md-2"></input>
	</div>	

<?php

$todosLosPedidos = $pedido->traerPedidosPendientes($numSuc);

?>

<div class="table-responsive">
	<table class="table table-hover table-condensed table-striped text-center">
		<thead class="thead-dark" style="font-size: 13px; font-weight: bold">
				<td >CANAL</td>
				<td style="width: 10%;">FECHA PED.</td>
				<td >PEDIDO</td>
				<td style="width: 15%;">ORDEN</td>
				<td >FACTURA</td>
				<td >CLIENTE</td>
				<td >DEPOSITO</td>
				<td >METODO ENTREGA</td>
				<td >GUIA</td>
				<td >FECHA GUIA</td>
				<td >IMPRIMIR</td>
				<td >RETIRO</td>
		</thead>

		<tbody id="table" class="text-center" style="font-size: 12px;">
			<?php
            foreach($todosLosPedidos as $valor => $key){
            ?>

			<tr >
				<td ><?= $key['ORIGEN'] ;?></td>
				<td ><?= $key['FECHA_PEDIDO']->format('Y-m-d') ;?></td>
				<td ><?= $key['PEDIDO'] ;?></td>
				<td ><?= $key['NRO_ORDEN_ECOMMERCE'] ;?></td>
				<td ><?= $key['N_COMP'] ;?></td>
				<td ><?= $key['RAZON_SOCIAL'] ;?></td>
				<td ><?= $key['DEPOSITO'] ;?></td>
				<td ><?= $key['METODO_ENVIO'] ;?></td>
				<td ><?= $key['GC_GDT_NUM_GUIA'] ;?></td>
				<td ><?php if(isset($key['FECHA'])){echo $key['FECHA']->format('Y-m-d');} ?></td>
				<td > <a href="remitoEntrega/?nComp=<?= $key['N_COMP'] ;?>" target=”_blank”> <i class="bi bi-file-richtext-fill" data-toggle="tooltip" data-placement="left" id="iconPrint"></i></a></td>
				<td  type="checkbox" ><i class="bi bi-check-circle-fill click" onclick="registrarOrden()" value=" <?= $key['NRO_ORDEN_ECOMMERCE'] ;?>"></i></td>
			</tr>

			<?php

			}

			?>

		</tbody>
	</table>
</div>
</form>

<?php
	}
?>

  <script src="main.js"></script>
  <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
   
	<script>

		$(function () {
		$('[data-toggle="tooltip"]').tooltip()
		})

	</script>

</body>

</html>