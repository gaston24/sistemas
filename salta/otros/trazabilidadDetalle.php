<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
?>
<!doctype HTML>
<html>
<head>
<title>Tracking Comprobrantes</title>
<meta charset="UTF-8"></meta>
<link rel="shortcut icon" href="../css/icono.jpg" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<style rel="stylesheet">
td{
	font-size:12px;
}
</style>

</head>
<body>
<div class="container">

<div class="d-flex justify-content-between mt-1">
<div ><h3>Trazabilidad Articulos Detalle</h3></div>
<div ><button type="button" class="btn btn-primary"onclick="window.location.href='trazabilidad.php'">Atras</button></div>
</div>  



<?php


//echo $desde.' '.$hasta.'- guia:'.$guia.' -cliente:'.$cliente.' -estado:'.$estado;

//echo $_SESSION['dsnLocal'];
$dsn = $_SESSION['dsnLocal'];

$user = 'sa';
$pass = 'Axoft1988';

$codArticu = $_GET['codArticu'];

$sql = "
SET DATEFORMAT YMD

SELECT CAST(A.FECHA_MOV AS date)FECHA_MOV, A.N_COMP, A.NCOMP_IN_S, A.NCOMP_ORIG, A.OBSERVACIO, A.T_COMP, A.TALONARIO, A.TCOMP_IN_S, CAST(B.CANTIDAD AS int)CANTIDAD
FROM STA14 A
INNER JOIN STA20 B
ON A.NCOMP_IN_S = B.NCOMP_IN_S AND A.TCOMP_IN_S = B.TCOMP_IN_S
WHERE A.FECHA_MOV >= GETDATE()-365
AND B.COD_ARTICU LIKE UPPER('$codArticu')

ORDER BY 1
";

$cid=odbc_connect($dsn, $user, $pass);

if (!$cid){
	exit("<strong>Ha ocurrido un error tratando de conectarse con el origen de datos.</strong>");
}

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

//echo $codArticu;

?>

<table class="table table-striped">
<thead>
	<tr>
		<th width="9%" class="h6">FECHA_MOV</th>
		<th width="9%" class="h6">TIPO COMP</th>
		<th width="9%" class="h6">N COMP</th>
		<th width="9%" class="h6">TALONARIO</th>
		<th width="9%" class="h6">TIPO COMP INTERNO</th>
		<th width="9%" class="h6">COMP INTERNO</th>
		<th width="9%" class="h6">COMP ORIG</th>
		<th width="9%" class="h6">OBSERVACIONES</th>
		<th width="9%" class="h6">CANTIDAD</th>
	</tr>
</thead>
<tbody>
<?php
while($v=odbc_fetch_array($result)){
?>
		<div class="row">
		
		
		<tr>
			<td width="9%"><?php echo $v['FECHA_MOV']?></td>
			<td width="9%"><?php echo $v['T_COMP']?></td>
			<td width="9%"><?php echo $v['N_COMP']?></td>
			<td width="9%"><?php echo $v['TALONARIO']?></td>
			<td width="9%"><?php echo $v['TCOMP_IN_S']?></td>			
			<td width="9%"><?php echo $v['NCOMP_IN_S']?></td>
			<td width="9%"><?php echo $v['NCOMP_ORIG']?></td>
			<td width="9%"><?php echo $v['OBSERVACIO']?></td>
			<td width="9%"><?php echo $v['CANTIDAD']?></td>
		</tr>
		</div>
<?php
}
?>
</tbody>
</table>

<div>

</div>
  
</div>
</body>

</html>	


<?php
}
?>