<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{
	
$permiso = $_SESSION['permisos'];
$user = $_SESSION['username'];
$rem = $_SESSION['rem'];
$codClient = $_GET['codClient'];

?>

<!DOCTYPE HTML>

<html>
<head>
<title>Control Remitos</title>	
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
<?php require_once __DIR__.'/../assets/css/header.php';?>
<link rel="stylesheet" href="css/style.css">

</head>
<body>	

<div>
	<div class="row mt-2">
		<a href="javascript:window.print();"><i class="fa fa-print" style="font-size: 2.2rem; margin-left: 2em" aria-hidden="true"></i></a>
		<button onClick="window.location.href= 'index.php'" class="btn btn-success" style="margin-left: 10em">Inicio</button>
	</div>
</div>

<div>

<div id="titleDif">
	<div>Remito: <a class="text-secondary"><?= $rem; ?></a></div>
	<div id="vendNom"></div>
</div>

</div>

<div class="container table-responsive tableDetalle">

<table class="table table-striped table-fh table-6c" id="id_tabla" align="center">
			
		<thead class="thead-dark">
			<tr style="font-size:smaller">
				<td>CODIGO</td>
				<td >DESCRIPCION</td>
				<td >CANT<br>REMITO</td>
				<td >CANT<br>CONTROL</td>
				<td >DIF</td>
        	</tr>
		</thead>
		<tbody >
		
		</tbody>
     		
</table>




</div>
<?php
}	

?>

<script>
	let nombreVen = '<?=$_SESSION['vendedor']; ?>';
	let user = '<?=$codClient?>'
	let articulosRemito = '<?=$_SESSION['articulosRemito'];?>';
	let articulosControlados = '<?=$_SESSION['articulosControlados'];?>';
</script>

<script src="js/procesados.js"></script>

</body>
</html>
