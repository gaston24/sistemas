<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
	
$permiso = $_SESSION['permisos'];
$user = $_SESSION['username'];


$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave="Axoft1988";
$cid=odbc_connect($dsn, $usuario, $clave);

?>
<!DOCTYPE HTML>

<html>
<head>
	<meta charset="utf-8">
	<title>Inventarios</title>	
	<link rel="shortcut icon" href="XL.png" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<body>	

<div style="margin: 5px">
<button onClick="volver()" class="btn btn-primary">Inicio</button>
</div>

<script>
	function volver() {window.location.href= 'index.php';};
</script>
	
<?php



$sql=
	"
	SET DATEFORMAT YMD
	SELECT CAST(FECHA AS DATE)FECHA, USUARIO, RUBRO, CANT, STOCK, DIF FROM SOF_INVENTARIO_RUBRO WHERE USUARIO = '$user' ORDER BY FECHA DESC

	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>
<div  style="margin-top:1%;margin-left:20%;margin-right:20%">



<table class="table table-striped" id="tabla">

		<tr >

				<td class="col-"><h4>FECHA</h4></td>
				
				<td class="col-"><h4>RUBRO</h4></td>
				
				<td class="col-"><h4>CANT CONTEO</h4></td>
								
				<td class="col-"><h4>STOCK</h4></td>
				
				<td class="col-"><h4>DIF</h4></td>
				
		</tr>

		
		<?php

		$sum = 0;
	   
		while($v=odbc_fetch_array($result)){

		?>

		
		<tr class="fila-base" >

				<td class="col-"><?php echo $v['FECHA'] ;?></td>
				
				<td class="col-"><?php echo $v['RUBRO'] ;?></td>
				
				<td class="col-"><?php echo $v['CANT'] ;?></td>
				
				<td class="col-"><?php echo $v['STOCK'] ;?></td>
	
				<td class="col-"><?php echo $v['DIF'] ;?></td>
				
		</tr>
		
			  
		
		<?php

		}

		?>

				
</table>

	
	


</div>


<?php
}
?>