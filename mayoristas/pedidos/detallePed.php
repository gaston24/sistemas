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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<body>	

<?php
	
$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave="Axoft1988";

$suc = $_GET['suc'];
$pedido = $_GET['pedido'];
$tipo = $_GET['tipo'];

$cid=odbc_connect($dsn, $usuario, $clave);



$sql=
	"
	SET DATEFORMAT YMD

	SELECT CAST(A.FECHA_PEDI AS DATE)FECHA, B.COD_ARTICU, C.DESCRIPCIO, CAST(B.CANT_PEDID AS FLOAT) CANT FROM GVA03 B
	INNER JOIN GVA21 A
	ON A.NRO_PEDIDO = B.NRO_PEDIDO AND A.TALON_PED = B.TALON_PED
	INNER JOIN STA11 C
	ON B.COD_ARTICU = C.COD_ARTICU
	WHERE A.TALON_PED IN (94) AND A.NRO_PEDIDO = '$pedido'
	AND A.COD_CLIENT = '$suc'
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>
<a href="historial.php"><img src="botonAtras.png"></a>
<a href="javascript:window.print();"><img src="print.png"></a>



</br>

<div align="center"><?php echo '<h3>Pedido: '.$pedido.' - '.$tipo.'</h3>' ?></div>
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

       
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr style="font-size:smaller; height: 5px">

				<td class="col-" style="height: 5px"><?php echo $v['FECHA'] ;?></a></td>
		
                <td class="col-" style="height: 5px"><?php echo $v['COD_ARTICU'] ;?></a></td>
				
				<td class="col-" style="height: 5px"><?php echo $v['DESCRIPCIO'] ;?></a></td>
				
				<td class="col-" style="height: 5px"><?php echo (int) ($v['CANT']) ;?></a></td>

                

        </tr>

		
        <?php

        }

        ?>

		
        		
</table>
</div>
<?php
}
?>