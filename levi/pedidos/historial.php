<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../../login.php");
}else{
	
$permiso = $_SESSION['permisos'];
?>
<!DOCTYPE HTML>

<html>
<head>

	<title>GUIAS POR LOCAL</title>	
	<?php include '../../../css/header.php'; ?>
<body>	




	<form action="" id="sucu" style="margin:20px">
	Elija sucursal:
	<select name="sucursal" form="sucu" >

		<option value="TODOS">Todos</option> 
		<option value="FRMAL2">MALVINAS</option> 
		<option value="FRSMI2">SAN MIGUEL</option> 
		<option value="FRBALL">VILLA BALLESTER</option> 
		
	
	</select >
	
<?php


if(!isset($_GET['desde'])){
	$ayer = date('Y-m').'-'.strright(('0'.((date('d')))),2);
}else{
	$desde = $_GET['desde'];
	$hasta = $_GET['hasta'];
}

//$ayer = date('Y-m').'-'.((date('d'))-1); 
//$ayer = ((date('d'))-1).'-'.date('m-Y');
?>
	
	Desde
	<input type="date" name="desde" value="<?php if(!isset($_GET['desde'])){ echo $ayer; } else { echo $desde ; }?>"></input>
	
	Hasta
	<input type="date" name="hasta" value="<?php if(!isset($_GET['hasta'])){ echo $ayer; } else { echo $hasta ; }?>"></input>
	
	
	
		<input type="submit" value="Consultar" class="btn btn-primary">
	</form>



<?php

if(isset ($_GET['sucursal'])){
	
$dsn = "1 - CENTRAL";
$suc = $_GET['sucursal'];


if($suc <> 'TODOS' ){

$suc = $_GET['sucursal'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];


$usuario = "sa";
$clave="Axoft1988";

$cid=odbc_connect($dsn, $usuario, $clave);





$sql=
	"
	
	SET DATEFORMAT YMD

	SELECT CAST(FECHA_PEDI AS DATE)FECHA, A.COD_CLIENT, A.NRO_PEDIDO, LEYENDA_1, CAST(B.CANT AS INT)CANT FROM GVA21 A
	INNER JOIN
	(
	SELECT NRO_PEDIDO, CAST(SUM(CANT_PEDID) AS FLOAT) CANT FROM GVA03 GROUP BY NRO_PEDIDO
	)B
	ON A.NRO_PEDIDO = B.NRO_PEDIDO
	WHERE COD_CLIENT IN
	(
		'FRMAL2',
		'FRSMI2',
		'FRBALL'
	) 
	AND FECHA_PEDI > (GETDATE()-60) 
	AND (FECHA_PEDI BETWEEN '$desde' AND '$hasta')
	AND A.COD_CLIENT = '$suc'
	ORDER BY 1 desc, 2 desc

	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));







?>
<div class="container">
<table class="table table-striped" >

        <tr >

				<td class="col-"><h4>CLIENTE</h4></td>
		
				<td class="col-"><h4>FECHA</h4></td>
		
                <td class="col-"><h4>PEDIDO</h4></td>
				
				<td class="col-"><h4>OBSERVAC</h4></td>
				
				<td class="col-"><h4>CANT</h4></td>  

                
        </tr>

		
        <?php

       
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr >

				<td class="col-"><?php echo $v['COD_CLIENT'] ;?></a></td>
		
                <td class="col-"><?php echo $v['FECHA'] ;?></a></td>
				
				<td class="col-"><a href="detallePed.php?pedido=<?php echo $v['NRO_PEDIDO'] ;?>&suc=<?php echo $v['COD_CLIENT'] ;?>&desde=<?php echo $desde ;?>&hasta=<?php echo $hasta ;?>"><?php echo $v['NRO_PEDIDO'] ;?></a></td>
				
				<td class="col-"><?php echo $v['LEYENDA_1'] ;?></a></td>
				
				<td class="col-"><?php echo $v['CANT'] ;?></a></td>

                

        </tr>

		
        <?php

        }

        ?>

		
        		
</table>
</div>
<?php

}

else{
	
	
$suc = "%";



?>

<div class="container">
<table class="table table-striped" >

        <tr >
		
				<td class="col-"><h4>CLIENTE</h4></td>
		
				<td class="col-"><h4>FECHA</h4></td>
		
                <td class="col-"><h4>PEDIDO</h4></td>
				
				<td class="col-"><h4>OBSERVAC</h4></td>
				
				<td class="col-"><h4>CANT</h4></td> 
				
				

        </tr>


<?php

				



		

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];

$usuario = "sa";
$clave="Axoft1988";

$cid=odbc_connect($dsn, $usuario, $clave);

if (!$cid){
	exit("<strong>Ha ocurrido un error tratando de conectarse con el origen de datos.</strong>");
}



$sql=
	"
	
	SET DATEFORMAT YMD

	SELECT CAST(FECHA_PEDI AS DATE)FECHA, A.COD_CLIENT, A.NRO_PEDIDO, LEYENDA_1, CAST(B.CANT AS INT)CANT FROM GVA21 A
	INNER JOIN
	(
	SELECT NRO_PEDIDO, CAST(SUM(CANT_PEDID) AS FLOAT) CANT FROM GVA03 GROUP BY NRO_PEDIDO
	)B
	ON A.NRO_PEDIDO = B.NRO_PEDIDO
	WHERE COD_CLIENT IN
	(
		'FRMAL2',
		'FRSMI2',
		'FRBALL'
	) 
	AND FECHA_PEDI > (GETDATE()-60) 
	AND (FECHA_PEDI BETWEEN '$desde' AND '$hasta')
	ORDER BY 1 desc, 2 desc
	
	

	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));





?>



		
        <?php

		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr >

				<td class="col-"><?php echo $v['COD_CLIENT'] ;?></a></td>
		
                <td class="col-"><?php echo $v['FECHA'] ;?></a></td>
				
				<td class="col-"><a href="detallePed.php?pedido=<?php echo $v['NRO_PEDIDO'] ;?>&suc=<?php echo $v['COD_CLIENT'] ;?>&desde=<?php echo $desde ;?>&hasta=<?php echo $hasta ;?>"><?php echo $v['NRO_PEDIDO'] ;?></a></td>
				
				<td class="col-"><?php echo $v['LEYENDA_1'] ;?></a></td>
				
				<td class="col-"><?php echo $v['CANT'] ;?></a></td>
				
				

               

        </tr>

		
        <?php

        }

        


echo '</table>	</div>';
}

}

}
?>