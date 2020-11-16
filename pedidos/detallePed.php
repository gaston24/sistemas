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
	WHERE A.TALON_PED IN (96, 97) AND A.NRO_PEDIDO = '$pedido'
	AND A.COD_CLIENT = '$suc'
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>

<div class="container">
	<div class="row mb-2">
		<div class="col-2"><a href="historial.php"><img src="imagenes/botonAtras.png"></a> </div>
		<div class="col-10"><?php echo '<h3>Pedido: '.$pedido.' - '.$tipo.'</h3>' ?></div>
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
			while($v=odbc_fetch_array($result)){
		?>
	
        <tr style="font-size:smaller; height: 5px">
			<td style="height: 5px"><?php echo $v['FECHA'] ;?></a></td>
			<td style="height: 5px"><?php echo $v['COD_ARTICU'] ;?></a></td>
			<td style="height: 5px"><?php echo $v['DESCRIPCIO'] ;?></a></td>
			<td style="height: 5px"><?php echo (int) ($v['CANT']) ;?></a></td>
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