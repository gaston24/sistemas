<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{
	
$permiso = $_SESSION['permisos'];
$user = $_SESSION['username'];

?>
<!DOCTYPE HTML>

<html>
<head>
<title>Control Remitos</title>	
<?php include '../../css/header_simple.php'; 

if(!isset($_GET['fechaDesde'])){
	$fechaDesde = date("Y-m-d");
	$fechaHasta = date("Y-m-d");
}else{
	$fechaDesde = $_GET['fechaDesde'];
	$fechaHasta = $_GET['fechaHasta'];
}

?>

</head>
<body>	


<div class="container">



</div>
<script>
function volver() {window.history.back();};
function procesar() {window.location.href= 'procesar.php?pedido=<?php echo $rem ; ?>';};
</script>

<div >
<form action="" method="GET" >

	<div class="form-group row">
		
		<div class="col-sm-1">
			<button type="button" class="btn btn-primary btn-sm" onClick="window.location.href= 'index.php'">Inicio</button>
		</div>
		
		<label class="col-sm-1 col-form-label">Desde</label>
		<div class="col-sm-2">
			<input type="date" class="form-control" name="fechaDesde" value="<?php echo $fechaDesde;?>">
		</div>
		
		<label class="col-sm-1 col-form-label">Hasta</label>
		<div class="col-sm-2">
			<input type="date" class="form-control" name="fechaHasta" value="<?php echo $fechaHasta;?>">
		</div>
				
		<div class="col-sm-2">
			<input type="submit" class="btn btn-primary btn-sm" value="Consultar">
		</div>
		
	</div>

</form>
</div>

<?php

if(isset($_GET['fechaDesde'])){


$dsn = '1 - CENTRAL';
$usuario = "sa";
$clave="Axoft1988";

$codClient = $_SESSION['codClient'];

$cid=odbc_connect($dsn, $usuario, $clave);


$sql=
	"
	SET DATEFORMAT YMD

	SELECT 

	CAST(A.FECHA_CONTROL AS DATE) FECHA_CONTROL, CAST(A.FECHA_REM AS DATE) FECHA_REM, 
	NOMBRE_VEN, A.NRO_REMITO, SUM(A.CANT_CONTROL) CANT_CONTROL, SUM(A.CANT_REM) CANT_REM, 
	SUM(A.CANT_CONTROL)-SUM(A.CANT_REM) DIFERENCIA
	
	FROM SJ_CONTROL_AUDITORIA A

	INNER JOIN GVA23 D
	ON A.USUARIO_LOCAL COLLATE Latin1_General_BIN = D.COD_VENDED
	WHERE A.COD_CLIENT = '$codClient' 
	AND (CAST( A.FECHA_CONTROL AS DATE) BETWEEN '$fechaDesde' AND '$fechaHasta' OR CAST( A.FECHA_REM AS DATE) BETWEEN '$fechaDesde' AND '$fechaHasta')
	
	GROUP BY A.NRO_REMITO, A.FECHA_REM, A.FECHA_CONTROL, NOMBRE_VEN
	ORDER BY A.FECHA_CONTROL
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>
<div >

<form method="get" action="diferencias_procesar.php">

<table class="table table-striped"  id="tabla">

        
		<thead>
			<tr style="font-size:smaller">
				<th >FECHA<br>REMITO</th>
				<th >NRO<br>REMITO</th>
				<th >FECHA<br>CONTROL</th>
				<th >USUARIO</th>
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
				<td ><a href="controlHistoricosDetalle.php?numRem=<?= $v['NRO_REMITO'] ;?>">  <?= $v['NRO_REMITO'] ;?> </a></td>
				<td ><?= $v['FECHA_CONTROL'] ;?></td>
				<td ><?= $v['NOMBRE_VEN'] ;?></td>
				<td ><?= $v['CANT_REM'] ;?></td>
				<td ><?= $v['CANT_CONTROL'] ;?></td>
				<td ><?= $v['DIFERENCIA'] ;?>
				</td>
				
        </tr>
		
        <?php

        }

        ?>
     		
</table>


</form>

</div>
        <?php

}

}


?>


</body>
</html>