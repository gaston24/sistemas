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
<link rel="shortcut icon" href="../../../css/icono.jpg" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>


</head>
<body>	

<a href="javascript:window.print();"><img src="print.png"></a>
<div style="margin: 5px">

<h3 align="center">Remito: <?php echo $rem; ?> - <div id="vendNom"></div> </h3>

</div>
<script>
function volver() {window.history.back();};
function procesar() {window.location.href= 'procesar.php?pedido=<?php echo $rem ; ?>';};
</script>


<?php

$dsn = '1 - CENTRAL';
$usuario = "sa";
$clave="Axoft1988";

$cid=odbc_connect($dsn, $usuario, $clave);



$sql=
	"
	SET DATEFORMAT YMD
	SELECT A.*, B.DESCRIPCIO, A.CANT_CONTROL-A.CANT_REM DIFERENCIA, A.FECHA_CONTROL, NOMBRE_VEN FROM
	(
		SELECT ISNULL(A.COD_CLIENT, B.COD_CLIENT)COD_CLIENT, ISNULL(A.COD_ARTICU, B.COD_ARTICU) COD_ARTICU, ISNULL(A.CANT_REM, 0)CANT_REM, ISNULL(B.CANT_CONTROL, 0)CANT_CONTROL, 
		ISNULL(A.NRO_REMITO, B.NRO_REMITO)NRO_REMITO, FECHA_CONTROL FROM SJ_CONTROL_LOCAL_AUX_REMITO A
		FULL OUTER JOIN (SELECT COD_CLIENT, NRO_REMITO, COD_ARTICU, SUM(CANT_CONTROL)CANT_CONTROL FROM SJ_CONTROL_LOCAL GROUP BY COD_CLIENT, NRO_REMITO, COD_ARTICU) B
		ON A.COD_CLIENT = B.COD_CLIENT AND A.NRO_REMITO = B.NRO_REMITO AND A.COD_ARTICU = B.COD_ARTICU
	)A
	INNER JOIN STA11 B
	ON A.COD_ARTICU COLLATE Latin1_General_BIN = B.COD_ARTICU COLLATE Latin1_General_BIN
	INNER JOIN SJ_CONTROL_AUDITORIA C
	ON A.COD_CLIENT = C.COD_CLIENT AND A.COD_ARTICU = C.COD_ARTICU AND A.NRO_REMITO = C.NRO_REMITO
	INNER JOIN GVA23 D
	ON C.USUARIO_LOCAL COLLATE Latin1_General_BIN = D.COD_VENDED
	WHERE A.COD_CLIENT = '$codClient' AND A.NRO_REMITO = '$rem'
	";

ini_set('max_execution_time', 300);

$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>
<div class="container">

<table class="table table-striped table-fh table-6c" id="id_tabla" style="width:90%;" align="center">
			
		<thead>
			<tr style="font-size:smaller">

				<td style="width: 10%"><h6>CODIGO</h6></td>
				
				<td style="width: 30%"><h6>DESCRIPCION</h6></td>
		
                <td style="width: 10%"><h6>CANT<br>REMITO</h6></td>
				
				<td style="width: 10%"><h6>CANT<br>CONTROL</h6></td>
				
				<td style="width: 10%"><h6>DIF</h6></td>

        </tr>
		</thead>
		<tbody>
        <?php
		$total1 = 0;
		$total2 = 0;
		$total3 = 0;
		while($v=odbc_fetch_array($result)){
		
		?>
		
			<?php 
			if($v['DIFERENCIA']<>0){
				?>
				<tr style="font-size:smaller;font-weight:bold;color:#FE2E2E" >
				<?php
			}else{
				?>
				<tr style="font-size:smaller" >
				<?php
			}
			?>

				<td style="width: 10%" ><?= $v['COD_ARTICU'] ;?></td>
		
                <td style="width: 30%" ><?= $v['DESCRIPCIO'] ;?></td>
				
				<td style="width: 10%; padding-left: 4%" ><?= $v['CANT_REM'] ;?></td>
				
				<td style="width: 10%; padding-left: 4%" ><?= $v['CANT_CONTROL'] ;?></td>
				
				<td style="width: 10%; padding-left: 4%" ><?= $v['DIFERENCIA'] ;?></td>

        </tr>
		
        <?php
		$total1+= $v['CANT_REM'];
		$total2+= $v['CANT_CONTROL'];
		$total3+= $v['DIFERENCIA'];
		$fechaControl = $v['FECHA_CONTROL'];
		$nombreVen = $v['NOMBRE_VEN'];
        }

        ?>
		<tr style="font-weight: bold;">
		
				<td style="width: 10%" ></td>
		
                <td style="width: 20%" >TOTALES</td>
				
				<td style="width: 10%; padding-left: 4%" ><?= $total1 ;?></td>
				
				<td style="width: 10%; padding-left: 4%" ><?= $total2 ;?></td>
				
				<td style="width: 10%; padding-left: 4%" ><?= $total3 ;?></td>

        </tr>
		
		</tbody>
     		
</table>
</br></br>



</div>
<?php
}	

$arrayNo = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '-');
$nombreVen = trim(str_replace($arrayNo, "", $nombreVen));

?>
<script>
	var date = new Date();
	var fechaControl = '<?= date_format(date_create($fechaControl),"Y/m/d H:i:s"); ?>';
	var nombreVen = '<?= $nombreVen; ?>';
	$(document).ready(function(){
		console.log(nombreVen);
		$('#vendNom').html(nombreVen + ' - ' + fechaControl);
	});
</script>

<button onClick="window.location.href= 'index.php'" class="btn btn-primary" style="margin-left:80%">Ir a Inicio</button>
<br><br>
</body>
</html>