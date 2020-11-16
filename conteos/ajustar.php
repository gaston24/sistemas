<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
	
$permiso = $_SESSION['permisos'];
$user = $_SESSION['username'];

$_SESSION['sucursal'] = $_GET['sucursal'];
$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave="Axoft1988";
$cid=odbc_connect($dsn, $usuario, $clave);

$sucursal = $_SESSION['sucursal'];

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
</div>

<script>
	function volver() {window.location.href= 'index.php';};
</script>
	
<?php



$sql=
	"
	SET DATEFORMAT YMD
	SELECT ID, FECHA, COD_ARTICU, CANT, CANT_STOCK, DIF, OBSERVAC, DESCRIPCIO, CASE WHEN STOCK_ACTUAL IS NULL THEN 0 ELSE STOCK_ACTUAL END STOCK_ACTUAL
	FROM
	(
		SELECT A.ID, CAST(FECHA AS DATE)FECHA, A.COD_ARTICU, A.CANT, A.CANT_STOCK, A.DIF, A.OBSERVAC, B.DESCRIPCIO, CAST(C.CANT_STOCK AS INT) STOCK_ACTUAL
		FROM SOF_INVENTARIO_AUDITA A
		INNER JOIN STA11 B
		ON A.COD_ARTICU = B.COD_ARTICU
		LEFT JOIN STA19 C
		ON A.COD_ARTICU = C.COD_ARTICU
		WHERE AUDITORIA IS NULL
		AND A.USUARIO = '$sucursal'
		AND COD_DEPOSI = '01'
	)A
	
	ORDER BY FECHA DESC

	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>
<div  style="margin-top:1%">

<form method="POST" action="reAjustar.php">

	<table class="table table-striped" id="tabla">

			<tr >

					<td class="col-"></td>
			
					<td class="col-"><h5>FECHA</h5></td>
										
					<td class="col-"><h5>CODIGO</h5></td>
					
					<td class="col-"></td>
					
					<td class="col-"><h5>DESCRIPCION</h5></td>
			
					<td class="col-"><h5>CANT</h5></td>
			
					<td class="col-"><h5>STOCK</h5></td>
					
					<td class="col-"><h5>ACTUAL</h5></td>
			
					<td class="col-"><h5>DIF</h5></td>
									
					<td class="col-"><h5>OBSERVAC</h5></td>
					
					<td class="col-"><h5>AUDITORIA</h5></td>
					
					<td class="col-"><h5>AJUSTAR</h5></td>
					
					<td class="col-"><h5>CANT</h5></td>
					
			</tr>

			
			<?php

			$sum = 0;
		   
			while($v=odbc_fetch_array($result)){

			?>

			
			<tr class="fila-base" style="font-size:smaller">

					<td class="col-"><input type="text" name="id[]" value="<?php echo $v['ID'] ;?>" hidden></td>
					
					<td class="col-"><?php echo $v['FECHA'] ;?></td>
									
					<td class="col-"><?php echo $v['COD_ARTICU'] ;?></td>
					
					<td class="col-"><input type="text" name="codArticu[]" value="<?php echo $v['COD_ARTICU'] ;?>" hidden></td>
					
					<td class="col-"><?php echo $v['DESCRIPCIO'] ;?></td>
					
					<td class="col-"><?php echo $v['CANT'] ;?></td>
					
					<td class="col-"><?php echo $v['CANT_STOCK'] ;?></td>
					
					<td class="col-"><?php echo $v['STOCK_ACTUAL'] ;?></td>
					
					<td class="col-"><?php echo $v['DIF'] ;?></td>
														
					<td class="col-"><?php echo $v['OBSERVAC'] ;?></td>
					
					<td class="col-"><input type="text" name="audita[]"></td>
					
					<td class="col-"><select name="ajustar[]"><option value="1">Si</option><option value="0">No</option></select></td>
					
					<td class="col-"><input type="text" name="cantAjuste[]" size="2" value="<?php echo $v['DIF'] ;?>"></td>
										
			</tr>
			
				  
			
			<?php

			}

			?>

					
	</table>

	<input type="submit" value="Enviar" class="btn btn-primary btn-sm" style="margin-left:80%;margin-bottom:10%">
	
</form>

</div>


<?php
}
?>