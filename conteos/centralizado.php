<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
	
$permiso = $_SESSION['permisos'];
$user = $_SESSION['username'];


$dsn = '1 - CENTRAL';
$usuario = "sa";
$clave="Axoft1988";
$cid=odbc_connect($dsn, $usuario, $clave);


function strright($rightstring, $length) {
  return(substr($rightstring, -$length));
}

if(!isset($_GET['desde'])){
	$ayer = date('Y-m').'-'.strright(('0'.((date('d')))),2);
}else{
	$desde = $_GET['desde'];
	$hasta = $_GET['hasta'];
}

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
<button onClick="exportar()" class="btn btn-outline-success">Exportar</button>
</div>

<script>
	function volver() {window.location.href= 'index.php';};
	function exportar() {window.location.href= 'exportar.php?desde=<?php echo $_GET['desde']?>&hasta=<?php echo $_GET['hasta']?>&rubro=<?php echo $_GET['rubro']?>';};
</script>



<form action="" id="sucu" style="margin:20px">
	Elija sucursal:
	<select name="sucursal" form="sucu" >
		<option value="%">TODOS</option>
		<option value="GTNUNI">Unicenter</option>
		<option value="GTALPA">Alto Palermo</option>
		<option value="GTAVEL">Avellaneda</option>
		<option value="GTABAS">Abasto</option>
		<option value="GTMORE">Moreno</option>
		<option value="GTSOL">Solar</option>
		<option value="GTTOM">Tortugas</option>
		<option value="GTCAB">Cabildo</option>
		<option value="GTROSA">ROSARIO - Siglo</option>
		<option value="GTMARD">MDQ - Shopping</option>
		<option value="GTMDP3">MDQ - Aldrey</option>
		<option value="GTVPAR">Villa del Parque</option>
		<option value="GTFLOR">Flores</option>
		<option value="GTALRO">ROSARIO - Alto Rosario</option>
		<option value="GTCABA">Caballito</option>
		<option value="GTGALE">Galerias</option>
		<option value="GTMDP2">MDQ - Guemes</option>
		<option value="GTPORT">Portal</option>
		<option value="GTDOT">DOT</option>
		<option value="GTPAGA">ROSARIO - Palace</option>
		<option value="GTGURR">Gurruchaga</option>
		<option value="GTNFLO">Flores 2</option>
		<option value="GTSHSO">Soleil</option>
				
	</select >
	
	Elija Rubro
	<select name="rubro" >
		<option value="%">TODOS</option>
		<option value="ACCESORIOS DE CUERO">ACCESORIOS DE CUERO</option>
		<option value="ACCESORIOS DE VINILICO">ACCESORIOS DE VINILICO</option>
		<option value="ACCESORIOS OUTLET">ACCESORIOS OUTLET</option>
		<option value="ALHAJEROS">ALHAJEROS</option>
		<option value="BILLETERAS DE CUERO">BILLETERAS DE CUERO</option>
		<option value="BILLETERAS DE VINILICO">BILLETERAS DE VINILICO</option>
		<option value="CALZADOS">CALZADOS</option>
		<option value="CALZADOS OUTLET">CALZADOS OUTLET</option>
		<option value="CAMPERAS">CAMPERAS</option>
		<option value="CAMPERAS OUTLET">CAMPERAS OUTLET</option>
		<option value="CARTERAS DE CUERO">CARTERAS DE CUERO</option>
		<option value="CARTERAS DE VINILICO">CARTERAS DE VINILICO</option>
		<option value="CHALINAS">CHALINAS</option>
		<option value="CINTOS DE CUERO">CINTOS DE CUERO</option>
		<option value="CINTOS DE VINILICO">CINTOS DE VINILICO</option>
		<option value="COSMETICA">COSMETICA</option>
		<option value="CUERO OUTLET">CUERO OUTLET</option>
		<option value="EQUIPAJES">EQUIPAJES</option>
		<option value="LENTES">LENTES</option>
		<option value="LLAVEROS">LLAVEROS</option>
		<option value="PACKAGING">PACKAGING</option>
		<option value="PARAGUAS">PARAGUAS</option>
		<option value="RELOJES">RELOJES</option>
		<option value="SINTETICOS OUTLET">SINTETICOS OUTLET</option>
		<option value="_DISCONTINUO CALZADO">DISCONTINUO CALZADO</option>
		<option value="_DISCONTINUO CUERO">DISCONTINUO CUERO</option>
		<option value="_DISCONTINUO VINILICO">DISCONTINUO VINILICO</option>
		<option value="_KITS">KITS</option>
	</select >
	
	Desde
	<input type="date" name="desde" value="<?php if(!isset($_GET['desde'])){ echo $ayer; } else { echo $desde ; }?>"></input>
	
	Hasta
	<input type="date" name="hasta" value="<?php if(!isset($_GET['hasta'])){ echo $ayer; } else { echo $hasta ; }?>"></input>
	
	
		<input type="submit" value="Consultar" class="btn btn-primary btn-sm"> 
	</form>

	
<?php



if(isset($_GET['desde'])){

$rubro = $_GET['rubro'];
$sucursal = $_GET['sucursal'];


$sql=
	"
	SET DATEFORMAT YMD
	SELECT CAST(FECHA AS DATE)FECHA, USUARIO, RUBRO, CANT, STOCK, DIF FROM SOF_INVENTARIO_RUBRO WHERE (FECHA BETWEEN '$desde' AND '$hasta') AND RUBRO LIKE '%$rubro%' AND USUARIO LIKE '%$sucursal%'

	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));


?>



<div  style="margin-top:3%;margin-left:20%;margin-right:20%">


<table class="table table-striped" id="tabla">

		<tr >

				<td class="col-"><h4>FECHA</h4></td>
				
				<td class="col-"><h4>SUCURSAL</h4></td>
				
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
				
				<td class="col-"><?php echo $v['USUARIO'] ;?></td>
				
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
}
?>