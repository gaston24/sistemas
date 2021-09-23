<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{
	
$permiso = $_SESSION['permisos'];
$user = $_SESSION['username'];
$numRem = $_GET['numRem'];
$codClient = $_SESSION['codClient'];

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



</div>
<script>
function volver() {window.history.back();};
function procesar() {window.location.href= 'procesar.php?pedido=<?php echo $rem ; ?>';};
</script>


<?php

if(isset($_GET['numRem'])){


$dsn = '1 - CENTRAL';
$usuario = "sa";
$clave="Axoft1988";

$cid=odbc_connect($dsn, $usuario, $clave);


$sql=
	"
	SET DATEFORMAT YMD

	SELECT 

	CAST(A.FECHA_CONTROL AS DATE) FECHA_CONTROL, CAST(A.FECHA_REM AS DATE) FECHA_REM, 
	NOMBRE_VEN, A.NRO_REMITO, 
	A.COD_ARTICU, B.DESCRIPCIO,
	A.CANT_CONTROL, A.CANT_REM, A.CANT_CONTROL - A.CANT_REM DIFERENCIA
		
	FROM SJ_CONTROL_AUDITORIA A
	INNER JOIN STA11 B
	ON A.COD_ARTICU COLLATE Latin1_General_BIN = B.COD_ARTICU COLLATE Latin1_General_BIN
	INNER JOIN GVA23 D
	ON A.USUARIO_LOCAL COLLATE Latin1_General_BIN = D.COD_VENDED
	WHERE A.NRO_REMITO = '$numRem' 
	";
	
	if(strtolower($user) != 'ramiro'){ 
		$sql.= "AND A.COD_CLIENT = '$codClient'";
	}	
	
	$sql.= "ORDER BY A.FECHA_CONTROL";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>
<div >

<div class="row">
	<div class="col-3"></div>
	<div class="col-6" id="datosRemito"></div>
	<div class="col-3"></div>
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
			</tr>
		</thead>
        <?php

		while($v=odbc_fetch_array($result)){
		
		?>
		
        <tr class="fila-base" style="font-size:smaller">

				<td ><?= $v['FECHA_REM'] ;?></td>
				<td ><?= $v['FECHA_CONTROL'] ;?></td>

				<td ><?= $v['COD_ARTICU'] ;?></td>
				<td ><?= $v['DESCRIPCIO'] ;?></td>
				<td ><?= $v['CANT_REM'] ;?></td>
				<td ><?= $v['CANT_CONTROL'] ;?></td>
				<td ><?= $v['DIFERENCIA'] ;?>
				</td>
				
        </tr>
		
        <?php

		$nombreVen = $v['NOMBRE_VEN'];

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
		$('#datosRemito').html('<h3><?=$numRem?> - <?=$nombreVen?></h3>');
	});
</script>
</body>
</html>