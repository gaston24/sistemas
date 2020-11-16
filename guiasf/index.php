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
	<title>GUIAS POR LOCAL</title>	
	<link rel="shortcut icon" href="icono.jpg" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<body>	



	<form action="" id="sucu" style="margin:20px">
	
<a href="../index.php"><img src="botonAtras.png"></a>
	
<?php
function strright($rightstring, $length) {
  return(substr($rightstring, -$length));
}

if(!isset($_GET['desde'])){
	$ayer = date('Y-m').'-'.strright(('0'.((date('d'))-1)),2);
}else{
	$desde = $_GET['desde'];
	$hasta = $_GET['hasta'];
}

//$ayer = date('Y-m').'-'.((date('d'))-1); 
//$ayer = ((date('d'))-1).'-'.date('m-Y');
?>
	
	Desde
	<input type="date" name="desde" value="<?php if(!isset($_GET['desde'])){ echo $ayer; } else { echo $desde ; }?>"></input>
	
	Hasta
	<input type="date" name="hasta" value="<?php if(!isset($_GET['hasta'])){ echo $ayer; } else { echo $hasta ; }?>"></input>
	
	<input type="text" name="remito" placeholder="Ingrese remito" ></input>
	
		<input type="submit" value="Consultar" class="btn btn-primary">
	</form>



<?php

if(isset ($_GET['desde'])){
	
$dsn = "1 - CENTRAL";





$suc = $_SESSION['codClient'];
//$numsuc = $_SESSION['numsuc'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];

if($_GET['remito']==''){
	$remito = '%';
}else{
	$remito = $_GET['remito'];
}

//echo $remito;

$usuario = "sa";
$clave="Axoft1988";

$cid=odbc_connect($dsn, $usuario, $clave);


$sql=
	"
	SET DATEFORMAT YMD

	EXEC SJ_RELACION_PED_REM_FAC '$suc', '$desde', '$hasta', '$remito'

	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));


?>
<div class="container">
<table class="table table-striped" style="margin-left:20px; margin-right:50px">

        <tr >

				<td class="col-"><h4>CLIENTE</h4></td>
		
				<td class="col-"><h4>FECHA_COMP</h4></td>
		
		        <td class="col-"><h4>FACTURA</h4></td>
		
                <td class="col-"><h4>REMITO</h4></td>
				
				<td class="col-"><h4>NRO_GUIA</h4></td>
				
				<!--<td class="col-"><h4>FECHA_GUIA</h4></td>  -->

                
        </tr>

		
        <?php

       
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr >

				<td class="col-"><?php echo $suc ;?></td>
		
                <td class="col-"><?php echo $v['FECHA_EMIS'] ;?></td>
				
				<td class="col-"><?php echo $v['N_COMP'] ;?></td>
				
				<td class="col-"><a href="detalleRem.php?remito=<?php echo $v['REMITO'] ;?>&suc=<?php echo $suc ;?>&desde=<?php echo $desde ;?>&hasta=<?php echo $hasta; ?>"><?php echo $v['REMITO'] ;?></a></td>
				
				<td class="col-"><?php echo $v['GUIA'] ;?></td>
				
				<!--<td class="col-"><?php //echo $v['FECHA_GUIA'] ;?></td>-->

                

        </tr>

		
        <?php

        }

        ?>

		
        		
</table>
</div>
<?php

}


}


?>