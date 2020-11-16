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

<?php
if(!isset($_GET['codArticu'])){	$codArticu = '%';}else{	$codArticu = $_GET['codArticu'].'%';}
?>

<div class="d-flex justify-content-between mt-1">
<div ><h3>Trazabilidad Articulos</h3></div>
<div ><button type="button" class="btn btn-primary"onclick="window.location.href='../index.php'">Atras</button></div>
</div>  

<form method="POST" action="#">
	<div class="row mb-1">
	
    <div class="col-sm-2">
	<label class="col-sm col-form-label">Articulo:</label>
		<input type="text" class="form-control form-control-sm" name="codArticu" autofocus>
    </div>
  
	<div class="col-sm-1 pt-4">
		<input type="submit" class="btn btn-primary" value="Buscar">
    </div>
  
  </div>
  
  
  
</form>  

<?php
if(isset($_POST['codArticu'])){

$dsnLocal = $_SESSION['dsnLocal'];
$user = 'sa';
$pass = 'Axoft1988';

$codArticu = $_POST['codArticu'].'%';




$sql = "
SET DATEFORMAT YMD

SELECT A.COD_ARTICU, MOVIMIENTOS, CAST(B.CANT_STOCK AS INT ) STOCK_SISTEMA FROM
(
SELECT COD_ARTICU,SUM( CASE TIPO_MOV WHEN 'S' THEN CANT *-1 ELSE CANT END)MOVIMIENTOS
FROM
(
SELECT A.FECHA_MOV, COD_ARTICU, CAST(CANTIDAD AS INT)CANT, B.TCOMP_IN_S, TIPO_MOV, N_RENGL_S , A.SUC_DESTIN, A.SUC_ORIG, A.N_COMP, A.NCOMP_ORIG, A.LEYENDA1
FROM STA20 B
INNER JOIN STA14 A
ON A.TCOMP_IN_S = B.TCOMP_IN_S AND A.NCOMP_IN_S = B.NCOMP_IN_S
WHERE B.COD_ARTICU LIKE UPPER('$codArticu%')
--ORDER BY A.FECHA_MOV

)A
GROUP BY COD_ARTICU
)A
LEFT JOIN STA19 B
ON A.COD_ARTICU = B.COD_ARTICU
ORDER BY 1
";

//echo $dsnLocal.'*'.$user.'*'.$pass;

$cid=odbc_connect($dsnLocal, $user, $pass);

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
		<th width="9%" class="h6">CODIGO</th>
		<th width="9%" class="h6">MOVIMIENTOS</th>
		<th width="9%" class="h6">STOCK SISTEMA</th>

	</tr>
</thead>
<tbody>
<?php
while($v=odbc_fetch_array($result)){
?>
		<div class="row">
		
		
		<tr>
			<td width="9%"><a href="trazabilidadDetalle.php?codArticu=<?php echo $v['COD_ARTICU']?>"><?php echo $v['COD_ARTICU']?></a></td>
			<td width="9%"><?php echo $v['MOVIMIENTOS']?></td>
			<td width="9%"><?php echo $v['STOCK_SISTEMA']?></td>
		</tr>
		</div>
<?php
}
?>
</tbody>
</table>


<div>

</div>
<?php
}
?>


  
</div>
</body>

</html>	


<?php
}
?>