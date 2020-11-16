<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
	
$permiso = $_SESSION['permisos'];
$user = $_SESSION['username'];


$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave="Axoft1988";
$cid=odbc_connect($dsn, $usuario, $clave);

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
	SELECT A.ID, A.FECHA, A.COD_ARTICU, A.CANT, A.RUBRO, A.DESCRIPCIO, A.CANT_STOCK, (A.CANT-A.CANT_STOCK) DIF, B.OBSERVAC FROM
	(
		SELECT ID, FECHA, COD_ARTICU, CANT, RUBRO, DESCRIPCIO, CASE WHEN CANT_STOCK IS NULL THEN 0 ELSE CANT_STOCK END CANT_STOCK FROM
		(
			SELECT A.ID, CAST(A.FECHA AS DATE)FECHA, A.COD_ARTICU, A.CANT, A.RUBRO, B.DESCRIPCIO, CAST(C.CANT_STOCK AS INT)CANT_STOCK
			FROM SOF_INVENTARIO_HISTORICO A
			INNER JOIN STA11 B
			ON A.COD_ARTICU = B.COD_ARTICU
			LEFT JOIN STA19 C
			ON A.COD_ARTICU = C.COD_ARTICU
			
		)A
	)A
	LEFT JOIN SOF_INVENTARIO_AUDITA B
	ON A.ID = B.ID_APP
	WHERE A.FECHA >= GETDATE()-30
	AND OBSERVAC IS NULL
	ORDER BY ID

	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>
<div  style="margin-top:1%">

<form method="POST" action="paraAjustar.php">

	<table class="table table-striped" id="tabla">

			<tr >

					<td class="col-"><h4>FECHA</h4></td>
					
					<td class="col-"></td>
			
					<td class="col-"><h4>RUBRO</h4></td>
					
					<td class="col-"><h4>CODIGO</h4></td>
					
					<td class="col-"></td>
					
					<td class="col-"></td>
					
					<td class="col-"><h4>DESCRIPCION</h4></td>
			
					<td class="col-"><h5>CANTIDAD</h5></td>
					
					<td class="col-"></td>
									
					<td class="col-"><h5>SISTEMA</h5></td>
					
					<td class="col-"></td>
					
					<td class="col-"><h5>DIF</h5></td>
					
					<td class="col-"></td>
					
					<td class="col-"><h5>AJUSTAR</h5></td>
					
					<td class="col-"><h5>OBSERVACIONES</h5></td>
					
			</tr>

			
			<?php

			$sum = 0;
		   
			while($v=odbc_fetch_array($result)){

			?>

			
			<tr class="fila-base" style="font-size:smaller">

					<td class="col-"><?php echo $v['FECHA'] ;?></td>
					
					<td class="col-"><input type="text" name="fecha[]" value="<?php echo $v['FECHA'] ;?>" hidden></td>
							
					<td class="col-"><?php echo $v['RUBRO'] ;?></td>
					
					<td class="col-"><?php echo $v['COD_ARTICU'] ;?></td>
					
					<td class="col-"><input type="text" name="id[]" value="<?php echo $v['ID'] ;?>" hidden></td>
					
					<td class="col-"><input type="text" name="codArticu[]" value="<?php echo $v['COD_ARTICU'] ;?>" hidden></td>
			
					<td class="col-"><?php echo $v['DESCRIPCIO'] ;?></td>
					
					<td class="col-" align="center"><?php echo $v['CANT'] ;?></td>
					
					<td class="col-"><input type="text" name="cant[]" value="<?php echo $v['CANT'] ;?>" hidden></td>
					
					<td class="col-" align="center"><?php echo $v['CANT_STOCK'] ;?></td>
					
					<td class="col-"><input type="text" name="cantStock[]" value="<?php echo $v['CANT_STOCK'] ;?>" hidden></td>
					
					<td class="col-" align="center"><?php echo $v['DIF'] ;?></td>
					
					<td class="col-"><input type="text" name="dif[]" value="<?php echo $v['DIF'] ;?>" hidden></td>
					
					<td><select class="custom-select custom-select-sm" name="ajusta[]"><option value="0">No</option> <option value="1">Si</option></select></td>
					
					<?php if($v['OBSERVAC'] != ''){ ?>
					
						<td class="col-"><input type="text" name="observac[]" value="<?php echo $v['OBSERVAC'] ; ?>" readonly></td>
						
						<?php }elseif($v['DIF']==0){ ?>
					
						<td class="col-"><input type="text" name="observac[]" readonly></td>
					
						<?php }else{ ?>

						<td class="col-"><input type="text" name="observac[]"></td>
						
					<?php } ?>
					
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