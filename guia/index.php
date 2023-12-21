<?php 
session_start(); 
if(!isset($_SESSION['username'])  || ($_SESSION['usuarioUy'] == 1)){
	header("Location:login.php");
}else{
	
$permiso = $_SESSION['permisos'];
$suc = $_SESSION['codClient'];
?>
<!DOCTYPE HTML>

<html>
<head>
	
	<title>GUIAS POR LOCAL</title>	
<?php include '../assets/css/header_simple.php'; ?>
<body>	




	<form action="" id="sucu" style="margin:20px">
	
	<div class="form-row">
	
	<button type="button" class="btn btn-primary" style="margin-left:1%" onClick="window.location.href='../index.php'">Inicio</button>
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
	
	<label style="margin-left:1%; margin-right:1%">Desde:</label>
	<input type="date" name="desde" value="<?php if(!isset($_GET['desde'])){ echo $ayer; } else { echo $desde ; }?>" class="form-control form-control-sm col-md-2"></input>
	
	<label style="margin-left:1%; margin-right:1%">Hasta:</label>
	<input type="date" name="hasta" value="<?php if(!isset($_GET['hasta'])){ echo $ayer; } else { echo $hasta ; }?>" class="form-control form-control-sm col-md-2"></input>
	
	<label style="margin-left:1%; margin-right:1%">Remito:</label>
	<input type="text" name="remito" placeholder="Ingrese remito" class="form-control form-control-sm col-md-1"></input>
	
	<label style="margin-left:1%; margin-right:1%">Estado:</label>
	<select name="estado" class="form-control form-control-sm col-md-1">
	<option value="">TODOS</option>
	<option value="SI">SI</option>
	<option value="NO">NO</option>
	</select >
	
		<input type="submit" value="Consultar" class="btn btn-primary" style="margin-left:1%">
		
	</div>	
	</form>



<?php


	




if(isset($desde) ){

// $dsn = "1 - CENTRAL";
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$remito = $_GET['remito'];
$estado = $_GET['estado'];

include_once __DIR__.'/../class/conexion.php';
$conn = new Conexion;

$cid = $conn->conectar('central');


// $usuario = "sa";
// $clave="Axoft1988";

// $cid=odbc_connect($dsn, $usuario, $clave);





$sql=
	"
	
	SET DATEFORMAT YMD
	
	SELECT * FROM
	(

	SELECT A.*, CASE WHEN B.NRO_REMITO != '' THEN 'SI' ELSE 'NO' END ESTADO

	FROM
	(
		SELECT A.COD_CLIENT, CAST(FECHA_COMP AS DATE) FECHA_COMP, N_COMP, NRO_GUIA, CAST(FECHA_GUIA AS DATE ) FECHA_GUIA, OBSERVACIONES
		FROM (  SELECT  GVA12.COD_CLIENT,  GVA14.RAZON_SOCI,  GVA12.FECHA_EMIS AS FECHA_COMP,  GVA12.T_COMP,  GVA12.N_COMP,  GC_GDT_GUIA_ENCABEZADO.NUM_GUIA AS NRO_GUIA,  
		GC_GDT_GUIA_ENCABEZADO.FECHA AS FECHA_GUIA, GC_GDT_GUIA_ENCABEZADO.OBSERVACIONES  FROM GVA12  INNER JOIN GVA14 ON GVA12.COD_CLIENT = GVA14.COD_CLIENT  
		INNER JOIN GC_GDT_GUIA_ENCABEZADO ON GVA12.GC_GDT_NUM_GUIA = GC_GDT_GUIA_ENCABEZADO.NUM_GUIA  
		UNION ALL  
		SELECT  STA14.COD_PRO_CL AS COD_CLIENT,  GVA14.RAZON_SOCI,  STA14.FECHA_MOV AS FECHA_COMP,  STA14.T_COMP,  STA14.N_COMP,  
		GC_GDT_GUIA_ENCABEZADO.NUM_GUIA AS NRO_GUIA,  GC_GDT_GUIA_ENCABEZADO.FECHA AS FECHA_GUIA, GC_GDT_GUIA_ENCABEZADO.OBSERVACIONES  FROM STA14  
		INNER JOIN GVA14 ON STA14.COD_PRO_CL = GVA14.COD_CLIENT  
		INNER JOIN GC_GDT_STA14 ON STA14.TCOMP_IN_S = GC_GDT_STA14.TCOMP_IN_S AND STA14.NCOMP_IN_S = GC_GDT_STA14.NCOMP_IN_S  
		INNER JOIN GC_GDT_GUIA_ENCABEZADO ON GC_GDT_STA14.GC_GDT_NUM_GUIA = GC_GDT_GUIA_ENCABEZADO.NUM_GUIA  
		)A WHERE COD_CLIENT LIKE 'GT%' AND FECHA_GUIA >= GETDATE()-180
		AND (FECHA_COMP BETWEEN '$desde' AND '$hasta')
		AND COD_CLIENT LIKE '%$suc%'
		AND N_COMP LIKE '%$remito'
		)A
	LEFT JOIN (SELECT NRO_REMITO, COD_CLIENT FROM SJ_CONTROL_AUDITORIA GROUP BY NRO_REMITO, COD_CLIENT) B
	ON A.COD_CLIENT = B.COD_CLIENT COLLATE Latin1_General_BIN AND A.N_COMP = B.NRO_REMITO COLLATE Latin1_General_BIN
	
	)A
	
	WHERE ESTADO LIKE '%$estado'
	
	ORDER BY FECHA_GUIA, COD_CLIENT


	";

ini_set('max_execution_time', 300);
$result=sqlsrv_query($cid,$sql)or die(exit("Error en odbc_exec"));



?>

<div class="container">
<table class="table table-striped" >

        <tr >

				<td class="col-"><h6>CLIENTE</h6></td>
		
				<td class="col-"><h6>FECHA_COMP</h6></td>
		
                <td class="col-"><h6>N_COMP</h6></td>
				
				<td class="col-"><h6>CONTROLADO</h6></td>
				
				<td class="col-"><h6>NRO_GUIA</h6></td>
				
				<td class="col-"><h6>FECHA_GUIA</h6></td>  
				
				<td class="col-"><h6>OBSERVAC</h6></td>  

                
        </tr>

		
        <?php

       
		while($v=sqlsrv_fetch_array($result)){

        ?>

		
        <tr >

				<td class="col-"><?php echo $v['COD_CLIENT'] ;?></td>
		
                <td class="col-"><?php echo $v['FECHA_COMP']->format('d/m/Y') ;?></td>
				
				<td class="col-"><a href="detalleRem.php?remito=<?php echo $v['N_COMP'] ;?>&suc=<?php echo $v['COD_CLIENT'] ;?>&desde=<?php echo $desde ;?>&hasta=<?php echo $hasta ;?>"><?php echo $v['N_COMP'] ;?></a></td>
				
				<td class="col-"><?php echo $v['ESTADO'] ;?></td>
				
				<td class="col-"><?php echo $v['NRO_GUIA'] ;?></td>
				
				<td class="col-"><?php echo $v['FECHA_GUIA']->format('d/m/Y')  ;?></td>
				
				<td class="col-"><?php echo $v['OBSERVACIONES'] ;?></td>

                

        </tr>

		
        <?php

        }

        ?>

		
        		
</table>
</div>
<?php



}

}
?>