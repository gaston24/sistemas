<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/sistemas/class/conexion.php";
require_once "class/Pedidos.php";

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



$suc = $_SESSION['numsuc'];
$codClient = $_SESSION['codClient'];



$pedidos = new Pedidos();

$data = $pedidos->traerHistorial();



?>
<a href="../index.php"><img src="botonAtras.png"></a>

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

       
		foreach ($data as $key => $v) {

        ?>

		
        <tr >
		
                <td class="col-"><?php echo $v['FECHA'] ;?></td>
				
				<td class="col-"><a href="detallePed.php?pedido=<?php echo $v['NRO_PEDIDO'] ;?>&suc=<?php echo $codClient ;?>&tipo=<?php echo $v['LEYENDA_1'] ;?>"><?php echo $v['NRO_PEDIDO'] ;?></a></td>
				
				<td class="col-"><?php echo $v['LEYENDA_1'] ;?></td>

				<td class="col-" align="center"><?php echo (int)($v['CANT']) ;?></td>
				
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