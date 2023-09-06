<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/sistemas/class/conexion.php";
require_once "../class/Pedidos.php";

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
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<body>	

<?php
	

$suc = $_GET['suc'];
$pedidoSeleccionado = $_GET['pedido'];

$pedido = new Pedido();

$data = $pedido->traerDetallePedido( $pedidoSeleccionado, $suc);

?>
<a href="javascript:history.go(-1)"><img src="botonAtras.png"></a>
<a href="javascript:window.print();"><img src="print.png"></a>



</br>

<div align="center"><?php echo '<h3>Pedido: '.$pedidoSeleccionado.' </h3>' ?></div>
</br>

<div class="container">
<table class="table table-striped" style="margin-left:50px">

        <tr >

				<td class="col-"><h4>FECHA</h4></td>
		
                <td class="col-"><h4>CODIGO</h4></td>
				
				<td class="col-"><h4>DESCRIPCION</h4></td>
				
				<td class="col-"><h4>CANT</h4></td>  

                
        </tr>

		
        <?php

       
		foreach ($data as $key => $v) {

        ?>

		
        <tr style="font-size:smaller; height: 5px">

				<td class="col-" style="height: 5px"><?= $v['FECHA']->format("Y-m-d") ;?></a></td>
		
                <td class="col-" style="height: 5px"><?= $v['COD_ARTICU'] ;?></a></td>
				
				<td class="col-" style="height: 5px"><?= $v['DESCRIPCIO'] ;?></a></td>
				
				<td class="col-" style="height: 5px"><?= (int) ($v['CANT']) ;?></a></td>

                

        </tr>

		
        <?php

        }

        ?>

		
        		
</table>
</div>
<?php
}
?>