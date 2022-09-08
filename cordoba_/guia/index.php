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
	<title>GUIAS POR LOCAL</title>	
	<link rel="shortcut icon" href="XL.png" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<body>	




	<form action="" id="sucu" style="margin:20px">
	Elija sucursal:
	<select name="sucursal" form="sucu" >

		<option value="TODOS">Todos</option> 
		<option value="FRBAUD">BAULERA</option> 
		<option value="FRORCE">VELEZ</option> 
		<option value="FRORIG">DINO</option> 
		<option value="FRORNC">NUEVO CENTRO</option> 
		<option value="FRORSJ">SAN JUAN</option> 
		<option value="FRPASJ">PASEO DEL JOCKEY</option> 

		
	
	</select >
	
<?php
function strright($rightstring, $length) {
  return(substr($rightstring, -$length));
}

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
	
	<input type="text" name="remito" placeholder="Ingrese remito" ></input>
	
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
$remito = $_GET['remito'];

$usuario = "sa";
$clave="Axoft1988";

$cid=odbc_connect($dsn, $usuario, $clave);





$sql=
	"
	
	SET DATEFORMAT YMD
	SELECT COD_CLIENT CLIENTE, CAST(FECHA_COMP AS DATE) FECHA_COMP, N_COMP, NRO_GUIA, CAST(FECHA_GUIA AS DATE ) FECHA_GUIA, OBSERVACIONES
	FROM (  SELECT  GVA12.COD_CLIENT,  GVA14.RAZON_SOCI,  GVA12.FECHA_EMIS AS FECHA_COMP,  GVA12.T_COMP,  GVA12.N_COMP,  GC_GDT_GUIA_ENCABEZADO.NUM_GUIA AS NRO_GUIA,  
	GC_GDT_GUIA_ENCABEZADO.FECHA AS FECHA_GUIA, GC_GDT_GUIA_ENCABEZADO.OBSERVACIONES  FROM GVA12  INNER JOIN GVA14 ON GVA12.COD_CLIENT = GVA14.COD_CLIENT  
	INNER JOIN GC_GDT_GUIA_ENCABEZADO ON GVA12.GC_GDT_NUM_GUIA = GC_GDT_GUIA_ENCABEZADO.NUM_GUIA  
	UNION ALL  
	SELECT  STA14.COD_PRO_CL AS COD_CLIENT,  GVA14.RAZON_SOCI,  STA14.FECHA_MOV AS FECHA_COMP,  STA14.T_COMP,  STA14.N_COMP,  
	GC_GDT_GUIA_ENCABEZADO.NUM_GUIA AS NRO_GUIA,  GC_GDT_GUIA_ENCABEZADO.FECHA AS FECHA_GUIA, GC_GDT_GUIA_ENCABEZADO.OBSERVACIONES  FROM STA14  
	INNER JOIN GVA14 ON STA14.COD_PRO_CL = GVA14.COD_CLIENT  
	INNER JOIN GC_GDT_STA14 ON STA14.TCOMP_IN_S = GC_GDT_STA14.TCOMP_IN_S AND STA14.NCOMP_IN_S = GC_GDT_STA14.NCOMP_IN_S  
	INNER JOIN GC_GDT_GUIA_ENCABEZADO ON GC_GDT_STA14.GC_GDT_NUM_GUIA = GC_GDT_GUIA_ENCABEZADO.NUM_GUIA  
	)A WHERE COD_CLIENT LIKE 'FR%' AND FECHA_GUIA >= '2017-08-01' 
	AND (FECHA_COMP BETWEEN '$desde' AND '$hasta')
	AND COD_CLIENT LIKE '%$suc%'
	AND N_COMP LIKE '%$remito'
	ORDER BY FECHA_GUIA, COD_CLIENT

	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));







?>
<div class="container">
<table class="table table-striped" >

        <tr >

				<td class="col-"><h4>CLIENTE</h4></td>
		
				<td class="col-"><h4>FECHA_COMP</h4></td>
		
                <td class="col-"><h4>N_COMP</h4></td>
				
				<td class="col-"><h4>NRO_GUIA</h4></td>
				
				<td class="col-"><h4>FECHA_GUIA</h4></td>  
				
				<td class="col-"><h4>OBSERVAC</h4></td>  

                
        </tr>

		
        <?php

       
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr >

				<td class="col-"><?php echo $v['CLIENTE'] ;?></td>
		
                <td class="col-"><?php echo $v['FECHA_COMP'] ;?></td>
				
				<td class="col-"><a href="detalleRem.php?remito=<?php echo $v['N_COMP'] ;?>&suc=<?php echo $v['CLIENTE'] ;?>&desde=<?php echo $desde ;?>&hasta=<?php echo $hasta ;?>"><?php echo $v['N_COMP'] ;?></a></td>
				
				<td class="col-"><?php echo $v['NRO_GUIA'] ;?></td>
				
				<td class="col-"><?php echo $v['FECHA_GUIA'] ;?></td>
				
				<td class="col-"><?php echo $v['OBSERVACIONES'] ;?></td>

                

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
$remito = $_GET['remito'];


?>

<div class="container">
<table class="table table-striped" >

        <tr >
		
				<td class="col-"><h4>CLIENTE</h4></td>
		
				<td class="col-"><h4>FECHA_COMP</h4></td>
		
                <td class="col-"><h4>N_COMP</h4></td>
				
				<td class="col-"><h4>NRO_GUIA</h4></td>
				
				<td class="col-"><h4>FECHA_GUIA</h4></td> 
				
				<td class="col-"><h4>OBSERVAC</h4></td> 

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
	SELECT COD_CLIENT CLIENTE, CAST(FECHA_COMP AS DATE) FECHA_COMP, N_COMP, NRO_GUIA, CAST(FECHA_GUIA AS DATE ) FECHA_GUIA, OBSERVACIONES
	FROM (  SELECT  GVA12.COD_CLIENT,  GVA14.RAZON_SOCI,  GVA12.FECHA_EMIS AS FECHA_COMP,  GVA12.T_COMP,  GVA12.N_COMP,  GC_GDT_GUIA_ENCABEZADO.NUM_GUIA AS NRO_GUIA,  
	GC_GDT_GUIA_ENCABEZADO.FECHA AS FECHA_GUIA, GC_GDT_GUIA_ENCABEZADO.OBSERVACIONES  FROM GVA12  INNER JOIN GVA14 ON GVA12.COD_CLIENT = GVA14.COD_CLIENT  
	INNER JOIN GC_GDT_GUIA_ENCABEZADO ON GVA12.GC_GDT_NUM_GUIA = GC_GDT_GUIA_ENCABEZADO.NUM_GUIA  
	UNION ALL  
	SELECT  STA14.COD_PRO_CL AS COD_CLIENT,  GVA14.RAZON_SOCI,  STA14.FECHA_MOV AS FECHA_COMP,  STA14.T_COMP,  STA14.N_COMP,  
	GC_GDT_GUIA_ENCABEZADO.NUM_GUIA AS NRO_GUIA,  GC_GDT_GUIA_ENCABEZADO.FECHA AS FECHA_GUIA, GC_GDT_GUIA_ENCABEZADO.OBSERVACIONES  FROM STA14  
	INNER JOIN GVA14 ON STA14.COD_PRO_CL = GVA14.COD_CLIENT  
	INNER JOIN GC_GDT_STA14 ON STA14.TCOMP_IN_S = GC_GDT_STA14.TCOMP_IN_S AND STA14.NCOMP_IN_S = GC_GDT_STA14.NCOMP_IN_S  
	INNER JOIN GC_GDT_GUIA_ENCABEZADO ON GC_GDT_STA14.GC_GDT_NUM_GUIA = GC_GDT_GUIA_ENCABEZADO.NUM_GUIA  
	)A WHERE COD_CLIENT LIKE 'FR%' AND FECHA_GUIA >= '2017-08-01' 
	AND (FECHA_COMP BETWEEN '$desde' AND '$hasta')
	AND COD_CLIENT IN 
	(
	 'FRBAUD', 
	 'FRORCE',
	 'FRORIG',
	 'FRORNC',
	 'FRORSJ',
	 'FRPASJ'

	)
	AND N_COMP LIKE '%$remito'
	ORDER BY FECHA_GUIA, COD_CLIENT

	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));





?>



		
        <?php

		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr >

				<td class="col-"><?php echo $v['CLIENTE'] ;?></a></td>
		
                <td class="col-"><?php echo $v['FECHA_COMP'] ;?></a></td>
				
				<td class="col-"><a href="detalleRem.php?remito=<?php echo $v['N_COMP'] ;?>&suc=<?php echo $v['CLIENTE'] ;?>&desde=<?php echo $desde ;?>&hasta=<?php echo $hasta ;?>"><?php echo $v['N_COMP'] ;?></a></td>
				
				<td class="col-"><?php echo $v['NRO_GUIA'] ;?></a></td>
				
				<td class="col-"><?php echo $v['FECHA_GUIA'] ;?></a></td>
				
				<td class="col-"><?php echo $v['OBSERVACIONES'] ;?></td>

               

        </tr>

		
        <?php

        }

        


echo '</table>	</div>';
}

}

}
?>