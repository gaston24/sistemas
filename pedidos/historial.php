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
	<title>DETALLE PEDIDOS</title>	
	<link rel="shortcut icon" href="XL.png" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<body>	

<?php
require_once __DIR__.'/../class/pedido.php';

$pedido = new Pedido();
$suc = $_SESSION['numsuc'];
$codClient = $_SESSION['codClient'];
$pedidos = $pedido->traerHistorial($codClient);

?>
<a href="../index.php"><img src="imagenes/botonAtras.png"></a>

<div class="container">
<table class="table table-striped" style="margin-left:20px; margin-right:50px">

        <tr >

				<td class="col-"><h4>FECHA</h4></td>
		
				<td class="col-"><h4>NRO PEDIDO</h4></td>
		
		        <td class="col-"><h4>OBSERVACIONES</h4></td>
		
                <td class="col-"><h4>CANT ARTICULOS</h4></td>
				
				<!--<td class="col-"><h4>FECHA_GUIA</h4></td>  -->

                
        </tr>

		
        <?php

       
		foreach ($pedidos as $v) {
			$fecha = $v['FECHA']->format('d/m/Y'); 
        ?>

		
        <tr >
		
                <td class="col-"><?= $fecha ;?></td>
				
				<td class="col-"><a href="detallePed.php?pedido=<?= $v['NRO_PEDIDO'] ;?>&suc=<?= $codClient ;?>&tipo=<?= $v['LEYENDA_1'] ;?>"><?= $v['NRO_PEDIDO'] ;?></a></td>
				
				<td class="col-"><?= $v['LEYENDA_1'] ;?></td>

				<td class="col-" align="center"><?= (int)($v['CANT']) ;?></td>
				
				<!--<td class="col-"><?php //echo $v['FECHA_GUIA'] ;?></td>-->

                

        </tr>

		
        <?php

        }

        ?>

		
        		
</table>
</div>
<?php

}





?>