<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
	
$permiso = $_SESSION['permisos'];
?>
<!DOCTYPE HTML>

<html>
<head>
	<meta charset="utf-8">
	<title>DETALLE REMITOS</title>	
	<link rel="shortcut icon" href="XL.png" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<body>	

<?php
	
$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave="Axoft1988";

$suc = $_GET['suc'];
$rem = $_GET['remito'];

$cid=odbc_connect($dsn, $usuario, $clave);



$sql=
	"
	SET DATEFORMAT YMD
	SELECT CAST(A.FECHA_MOV AS DATE)FECHA, A.N_COMP REMITO, B.COD_ARTICU ARTICULO, C.DESCRIPCIO DESCRIPCION, CAST(B.CANTIDAD AS FLOAT)CANT FROM STA14 A
INNER JOIN STA20 B
ON A.NCOMP_IN_S = B.NCOMP_IN_S AND A.TCOMP_IN_S = B.TCOMP_IN_S
INNER JOIN STA11 C
ON C.COD_ARTICU = B.COD_ARTICU
WHERE A.FECHA_MOV >= '2017-08-01'
AND A.COD_PRO_CL LIKE '$suc'
AND A.N_COMP = '$rem'
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>
<a href="/sistemas/guia/index.php?sucursal=<?php echo $suc ?>&desde=<?php echo $_GET['desde'] ;?>&hasta=<?php echo $_GET['hasta'] ;?>&remito="><img src="botonAtras.png"></a>
<a href="javascript:window.print();"><img src="print.png"></a>



</br>
<div class="container">
<table class="table table-striped" style="margin-left:50px">

        <tr >

				<td class="col-"><h4>FECHA</h4></td>
		
				<td class="col-"><h4>REMITO</h4></td>
		
                <td class="col-"><h4>ARTICULO</h4></td>
				
				<td class="col-"><h4>DESCRIPCION</h4></td>
				
				<td class="col-"><h4>CANT</h4></td>  

                
        </tr>

		
        <?php

       
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr >

				<td class="col-"><?php echo $v['FECHA'] ;?></a></td>
		
                <td class="col-"><?php echo $v['REMITO'] ;?></a></td>
				
				<td class="col-"><?php echo $v['ARTICULO'] ;?></a></td>
				
				<td class="col-"><?php echo $v['DESCRIPCION'] ;?></a></td>
				
				<td class="col-"><?php echo (int) ($v['CANT']) ;?></a></td>

                

        </tr>

		
        <?php

        }

        ?>

		
        		
</table>
</div>
<?php
}
?>