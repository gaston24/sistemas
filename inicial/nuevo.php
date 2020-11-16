<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
	
$permiso = $_SESSION['permisos'];
$user = $_SESSION['username'];
$codClient = $_SESSION['codClient'];


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
<button onClick="volver()" class="btn btn-primary">Cancelar</button>
</div>

<script>
function volver() {window.location.href= 'index.php';};
</script>


	
<?php
	
	function strright($rightstring, $length) {
	  return(substr($rightstring, -$length));
	}
	
	$dia = date('Y-m').'-'.strright(('0'.(date('d'))),2);
	$hora = (date('G')-5).':'.date('i:s');
	$fechaHora = $dia.' '.$hora.':000' ;




$dsn = '1 - CENTRAL';
$usuario = "sa";
$clave="Axoft1988";

$cid=odbc_connect($dsn, $usuario, $clave);


$sql=
	"
	SET DATEFORMAT YMD
	SELECT * FROM SOF_CARGA_PEDIDO A
	INNER JOIN STA11 B
	ON A.COD_ARTICU COLLATE Latin1_General_BIN = B.COD_ARTICU 
	WHERE CONTENEDOR NOT IN
	(SELECT CONTENEDOR FROM SOF_CARGA_PEDIDO_RELACION WHERE COD_CLIENT = '$codClient')
	ORDER BY 1, 2
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));







?>
<div class="container">
<form action="procesar.php" method="POST">
<table class="table table-striped" style="margin-left:50px; margin-right:50px ; margin-bottom:30px" id="tabla">

        <tr >

				<td class="col-"><h4>FECHA</h4></td>
		
				<td class="col-"><h4>CONTENEDOR</h4></td>
				
				<td class="col-"><h4>CODIGO</h4></td>
		
                <td class="col-"><h4>DESCRIPCION</h4></td>
				
				<td class="col-"><h4>PEDIDO</h4></td>
				
		</tr>

		
        <?php

		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr class="fila-base">

				<td class="col-"><?php echo $v['FECHA'];?></td>
		
		        <td><input type="text" value="<?php echo $v['CONTENEDOR']; ;?>" name="contenedor[]" readonly</td>
		
                <td><input type="text" name="codArt[]" value="<?php echo $v['COD_ARTICU'] ;?>"></td>
				
				<td class="col-"><?php echo $v['DESCRIPCIO'] ;?></td>
				
				<td><input type="text" name="cantPed[]" value="1"></td>

		</tr>
		
		      
		
        <?php

        }

        ?>

</table>

<input type="submit" value="Confirmar" class="btn btn-primary" style="margin-left:80%; margin-top:20px " >

</form>

</div>


        <?php




}
?>