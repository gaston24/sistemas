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
	<title>Detalle de Pedidos</title>	
	<link rel="shortcut icon" href="../assets/images/logo.jpg" />
	<?php 
		require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/assets/css/css.php';
	?>

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/assets/css/fontawesome/css.php';?>
<body>	

<?php
require_once __DIR__.'/../class/pedido.php';

$pedido = new Pedido();
$suc = $_SESSION['numsuc'];
$codClient = $_SESSION['codClient'];
$pedidos = $pedido->traerHistorial($codClient);

?>
<a onCLick="window.location='../index.php'" class="ml-5 mt-5"><i class="fad fa-home fa-2xl" title="INICIO"></i></a>

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