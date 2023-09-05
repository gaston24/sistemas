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

	<title>Historial pedidos</title>	

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

	<!-- Bootstrap Icons -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<body>	




	<form class="form-inline" action="" id="sucu" style="margin:20px">
	Elija sucursal:
	<select class="form-control ml-1" name="sucursal" form="sucu" >

		<option value="TODOS">Todos</option> 
		<option value="FRBAUD">BAULERA</option> 
		<option value="FRORCE">VELEZ</option> 
		<option value="FRORIG">DINO</option> 
		<option value="FRORNC">NUEVO CENTRO</option> 
		<option value="FRORSJ">SAN JUAN</option> 
		<option value="FRPASJ">PASEO DEL JOCKEY</option> 

		
	
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
	
	<label class="ml-1">Desde: </label>
	<input class="form-control ml-1" type="date" name="desde" value="<?php if(!isset($_GET['desde'])){ echo $ayer; } else { echo $desde ; }?>"></input>
	
	<label class="ml-1">Hasta: </label>
	<input class="form-control ml-1" type="date" name="hasta" value="<?php if(!isset($_GET['hasta'])){ echo $ayer; } else { echo $hasta ; }?>"></input>
	
	
	
		<button type="submit" class="btn btn-primary ml-2">Consultar <i class="bi bi-search"></i></button>

		<button type="button" class="btn btn-warning" onClick="window.location.href='../index.php'" style="margin-left:20rem;">Volver <i class="bi bi-arrow-90deg-left"></i></button>
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

	SELECT CAST(FECHA_PEDI AS DATE)FECHA, A.COD_CLIENT, A.NRO_PEDIDO, LEYENDA_1, CAST(B.CANT AS INT)CANT FROM, 
	CASE WHEN ESTADO = 5 THEN 'ANULADO' ELSE 'APROBADO' END ESTADO FROM GVA21 A
	INNER JOIN
	(
	SELECT NRO_PEDIDO, CAST(SUM(CANT_PEDID) AS FLOAT) CANT FROM GVA03 GROUP BY NRO_PEDIDO
	)B
	ON A.NRO_PEDIDO = B.NRO_PEDIDO
	WHERE COD_CLIENT IN
	(
	'FRBAUD', 
	'FRORCE',
	'FRORIG',
	'FRORNC',
	'FRORSJ',
	'FRPASJ'
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
				
				<td class="col-"><h4>OBSERVACIONES</h4></td>
				
				<td class="col-"><h4>CANTIDAD</h4></td>  

				<td class="col-"><h4>ESTADO</h4></td>  
                
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

                <td class="col-"><?php echo $v['ESTADO'] ;?></a></td>

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


<table class="table table-striped table-bordered table-sm table-hover" style="width:70%; margin-left:1rem">
	<thead class="thead-dark" >
        <tr >
		
				<td style="position: sticky; top: 0; z-index: 10; background: #343a40; color: white;"><h6>CLIENTE</h6></td>
		
				<td style="position: sticky; top: 0; z-index: 10; background: #343a40; color: white;" class="col-"><h6>FECHA</h6></td>
		
                <td style="position: sticky; top: 0; z-index: 10; background: #343a40; color: white;" class="col-"><h6>PEDIDO</h6></td>
				
				<td style="position: sticky; top: 0; z-index: 10; background: #343a40; color: white;" class="col-"><h6>OBSERVACIONES</h6></td>
				
				<td style="position: sticky; top: 0; z-index: 10; background: #343a40; color: white;" class="col-"><h6>CANTIDAD</h6></td> 
				
				<td style="position: sticky; top: 0; z-index: 10; background: #343a40; color: white;" class="col-"><h6>ESTADO</h6></td> 

        </tr>
	</thead>

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

	SELECT CAST(FECHA_PEDI AS DATE)FECHA, A.COD_CLIENT, A.NRO_PEDIDO, LEYENDA_1, CAST(B.CANT AS INT)CANT, 
	CASE WHEN ESTADO = 5 THEN 'ANULADO' ELSE 'APROBADO' END ESTADO FROM GVA21 A
	INNER JOIN
	(
	SELECT NRO_PEDIDO, CAST(SUM(CANT_PEDID) AS FLOAT) CANT FROM GVA03 GROUP BY NRO_PEDIDO
	)B
	ON A.NRO_PEDIDO = B.NRO_PEDIDO
	WHERE COD_CLIENT IN
	(
	'FRBAUD', 
	'FRORCE',
	'FRORIG',
	'FRORNC',
	'FRORSJ',
	'FRPASJ'
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
				
				<td class="col-"><?php echo $v['ESTADO'] ;?></a></td>
               

        </tr>

		
        <?php

        }

        


echo '</table>	</div>';
}

}

}
?>

