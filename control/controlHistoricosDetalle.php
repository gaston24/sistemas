<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{

$permiso = $_SESSION['permisos'];
$user = $_SESSION['username'];
$numRem = $_GET['numRem'];
$codClient = $_SESSION['codClient'];

if(!isset($_GET['fechaDesde'])){
	$fechaDesde = date("Y-m-d");
	$fechaHasta = date("Y-m-d");
}else{
	$fechaDesde = $_GET['fechaDesde'];
	$fechaHasta = $_GET['fechaHasta'];
}

include_once __DIR__.'/../class/remito.php';
$remitos = new Remito();
$remitosHistoricosDetalle = $remitos->traerHistoricosDetalle($numRem);

?>
<!DOCTYPE HTML>

<html>
<head>
<title>Control Remitos</title>	
<meta charset="utf-8">
<link rel="shortcut icon" href="../../../css/icono.jpg" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>



</head>
<body>	


<div class="container">


<?php

if(isset($_GET['numRem'])){

?>
<div >

<div class="row">
	<div class="col-2"><button class="btn btn-primary btn-sm mt-1 ml-5 mb-1" onclick="location.href= document.referrer ">Volver</button></div>
	<div class="col-8" id="datosRemito"></div>
	<div class="col-2"></div>
</div>

<table class="table table-striped"  id="tabla">

        
		<thead>
			<tr style="font-size:smaller">
				<th >FECHA<br>REMITO</th>
				<th >FECHA<br>CONTROL</th>

				<th >CODIGO</th>
				<th >DESCRIPCION</th>
				<th >CANT REM</th>
				<th >CANT CONTROL</th>
				<th >CANT DIF</th>
				<th >PARTIDA</th>
			</tr>
		</thead>
        <?php

	foreach($remitosHistoricosDetalle as $data){
		$dateControl =$data['FECHA_CONTROL']->format('d/m/Y'); 
		
		$style= ($data['CANT_REM'] <> $data['CANT_CONTROL']) ? "font-size:smaller;font-weight:bold;color:#FE2E2E" : '';
		?>
        <tr class="fila-base" style="font-size:smaller; <?=$style?>"  >

				<td ><?= $data['FECHA_REM']->format('d/m/Y');?></td>
				<td ><?= $dateControl ;?></td>

				<td ><?= $data['COD_ARTICU'] ;?></td>
				<td ><?= $data['DESCRIPCIO'] ;?></td>
				<td ><?= $data['CANT_REM'] ;?></td>
				<td ><?= $data['CANT_CONTROL'] ;?></td>
				<td ><?= $data['DIFERENCIA'] ;?>
				<td ><?= $data['PARTIDA'] ;?>
				</td>
				
        </tr>
		
        <?php

		$nombreVen = $data['NOMBRE_VEN'];
		$numSuc = $data['SUC_DESTIN'];
		$codSuc = $data['COD_CLIENT'];
        }

        ?>
     		
</table>


</div>
<?php

}

}


?>

<script>

	$(document).ready(function(){
		$('#datosRemito').html('<h4><?=$codSuc?> (<?=$numSuc?> ) - <?=$numRem?> - <?=$nombreVen?></h4>');
	});
</script>
</body>
</html>