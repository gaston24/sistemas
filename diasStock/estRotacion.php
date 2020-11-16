<?php session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
	
function strright($rightstring, $length) {
  return(substr($rightstring, -$length));
}

if(!isset($_GET['desde'])){
	$ayer = date('Y-m').'-'.strright(('0'.((date('d'))-1)),2);
}else{
	$desde = $_GET['desde'];
	$hasta = $_GET['hasta'];
}
?>
<!DOCTYPE HTML>

<html>
<head>
	<meta charset="utf-8">
	<title>Rotacion</title>	
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<body>	




	<form action="" id="sucu" style="margin:20px">
		
	Elija Rubro
	<select name="rubro" >
		<option value="">TODOS</option>
		<option value="ACCESORIOS DE CUERO">ACCESORIOS DE CUERO</option>
		<option value="ACCESORIOS DE VINILICO">ACCESORIOS DE VINILICO</option>
		<option value="ACCESORIOS OUTLET">ACCESORIOS OUTLET</option>
		<option value="ALHAJEROS">ALHAJEROS</option>
		<option value="BILLETERAS DE CUERO">BILLETERAS DE CUERO</option>
		<option value="BILLETERAS DE VINILICO">BILLETERAS DE VINILICO</option>
		<option value="CALZADOS">CALZADOS</option>
		<option value="CALZADOS OUTLET">CALZADOS OUTLET</option>
		<option value="CAMPERAS">CAMPERAS</option>
		<option value="CAMPERAS OUTLET">CAMPERAS OUTLET</option>
		<option value="CARTERAS DE CUERO">CARTERAS DE CUERO</option>
		<option value="CARTERAS DE VINILICO">CARTERAS DE VINILICO</option>
		<option value="CHALINAS">CHALINAS</option>
		<option value="CINTOS DE CUERO">CINTOS DE CUERO</option>
		<option value="CINTOS DE VINILICO">CINTOS DE VINILICO</option>
		<option value="COSMETICA">COSMETICA</option>
		<option value="CUERO OUTLET">CUERO OUTLET</option>
		<option value="EQUIPAJES">EQUIPAJES</option>
		<option value="LENTES">LENTES</option>
		<option value="LLAVEROS">LLAVEROS</option>
		<option value="PACKAGING">PACKAGING</option>
		<option value="PARAGUAS">PARAGUAS</option>
		<option value="RELOJES">RELOJES</option>
		<option value="SINTETICOS OUTLET">SINTETICOS OUTLET</option>
		<option value="_DISCONTINUO CALZADO">DISCONTINUO CALZADO</option>
		<option value="_DISCONTINUO CUERO">DISCONTINUO CUERO</option>
		<option value="_DISCONTINUO VINILICO">DISCONTINUO VINILICO</option>
		<option value="_KITS">KITS</option>
	</select >
	Desde
	<input type="date" name="desde" value="<?php if(!isset($_GET['desde'])){ echo $ayer; } else { echo $desde ; }?>"></input>
	
	Hasta
	<input type="date" name="hasta" value="<?php if(!isset($_GET['hasta'])){ echo $ayer; } else { echo $hasta ; }?>"></input>
	
		<input type="submit" value="Consultar">
	</form>

<div class="container">

<?php



	$dsn = $_SESSION['dsn'];
	$suc = $_SESSION['numsuc'];
	$user = 'sa';
	$pass = 'Axoft1988';

$cid=odbc_connect($dsn, $user, $pass);

if (!$cid){
	echo 'Error en la conexion con '.$dsn;
	//continue;
}



$sql=
	"
SET DATEFORMAT YMD
SELECT C.DESC_SUCURSAL, A.RUBRO, A.STOCK, B.UNID_VENDIDAS, CAST(((A.STOCK / B.UNID_VENDIDAS)*30) AS DECIMAL(10,0)) DIAS_STOCK FROM 
(
	SELECT C.RUBRO, CAST((SUM(B.CANT_STOCK ))AS FLOAT)STOCK 
	FROM STA11 A 
	LEFT JOIN STA19 B ON B.COD_ARTICU = A.COD_ARTICU 
	LEFT JOIN SOF_RUBROS_TANGO C ON A.COD_ARTICU = C.COD_ARTICU 
	WHERE B.CANT_STOCK <> 0 GROUP BY C.RUBRO
)A
INNER JOIN
(
	SELECT C.RUBRO, CAST(SUM(B.IMP_NETO_P) AS FLOAT) IMPORTE, CAST(SUM(B.CANTIDAD) AS FLOAT)UNID_VENDIDAS FROM GVA12 A 
	INNER JOIN GVA53 B ON A.N_COMP = B.N_COMP AND A.FECHA_EMIS = B.FECHA_MOV INNER JOIN SOF_RUBROS_TANGO C ON B.COD_ARTICU = C.COD_ARTICU 
	WHERE A.FECHA_EMIS BETWEEN '$desde' AND '$hasta' GROUP BY C.RUBRO
) B
ON A.RUBRO = B.RUBRO
, SUCURSAL C
WHERE ID_SUCURSAL = (SELECT ID_SUCURSAL FROM EMPRESA)
AND A.RUBRO != 'PACKAGING'


	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
?>

<table class="table table-striped">

        <tr style="font-weight:bold">

				<td align="center">SUCURSAL</td>
                <td align="center">RUBRO</td>
                <td align="center">STOCK</td>
				<td align="center">UNID VENDIDAS</td>
				<td align="center">STOCK DIAS</td>
				
        </tr>

		
        <?php

       
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr >

                <td><?php echo $v['DESC_SUCURSAL'] ;?></a></td>
				
				<td><?php echo $v['RUBRO'] ;?></a></td>

                <td align="center"><?php echo (int) ($v['STOCK']) ;?></td>

				<td align="center"><?php echo (int) ($v['UNID_VENDIDAS']) ;?></td>
				
				<td align="center"><?php echo (int) ($v['DIAS_STOCK']) ;?></td>
				
				

        </tr>

		
        <?php

        }

        ?>

		
        <tr >

                <!--<td colspan="13"><font face="arial" size="2">Total registros: <?php echo odbc_num_rows($result); ?></font></td>-->

        </tr>

		
        <tr >

                <!--<td colspan="13"><font face="arial" size="2">Total registros: <?php echo odbc_num_rows($result); ?></font></td>-->

        </tr>
		
</table>

</div>

</html>
<?php
}
?>