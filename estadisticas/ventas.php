<!DOCTYPE HTML>
<html>
<head>
	<title>Ventas - Franquicias</title>	
	<?php include '../../css/header_simple.php' ?>
</head>
<body>	

<form action="" id="sucu" style="margin:20px">
	Elija sucursal:
	<select name="sucursal" form="sucu" >
	<option value="TODOS">TODOS</option>

<?php

$dsn2 = "FRANQUICIAS";
$usuario = "sa";
$clave="Axoft";

$cid2=odbc_connect($dsn2, $usuario, $clave);

$sql="SELECT B.NRO_SUCURSAL, B.DESC_SUCURSAL FROM  SUCURSAL B WHERE NRO_SUCURSAL IN (844, 845, 866) ORDER BY 1";

ini_set('max_execution_time', 300);
$result2=odbc_exec($cid2,$sql)or die(exit("Error en odbc_exec"));

while($v=odbc_fetch_array($result2)){
	 echo '<option value="'.$v['NRO_SUCURSAL'].'">'.$v['DESC_SUCURSAL'].'</option>'  ;
}
echo '</select >';
	


if(!isset($_GET['desde'])){
	$ayer = date('Y-m').'-'.strright(('0'.((date('d'))-1)),2);
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
	
		<input type="submit" value="Consultar" class="btn btn-primary btn-sm" style="margin-bottom:4px">
	</form>



<?php

if(isset ($_GET['sucursal'])){
	
$dsn = "FRANQUICIAS";

$suc = $_GET['sucursal'];

if($suc <> 'TODOS' ){

$suc = $_GET['sucursal'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];

$usuario = "sa";
$clave="Axoft";

$cid=odbc_connect($dsn, $usuario, $clave);





$sql=
	"
	SET DATEFORMAT YMD
	
	SELECT * FROM
	(
	SELECT 
	CASE NUM WHEN 907 THEN 844 WHEN 911 THEN 845 WHEN 913 THEN 866 ELSE NUM END NUM,
	CASE SUCURSAL WHEN 'SOFIA SALTA NOA' THEN 'SALTA NOA' WHEN 'SOFIA PORTAL SALTA' THEN 'PORTAL SALTA' WHEN 'SOFIA PASEO SALTA' THEN 'PASEO SALTA' ELSE SUCURSAL END SUCURSAL,
	SUM(IMPORTE) IMPORTE
	FROM
	(
	SELECT NRO_SUCURSAL NUM, B.DESC_SUCURSAL SUCURSAL, SUM(CASE A.T_COMP WHEN 'NCR' THEN (A.IMPORTE*-1) ELSE A.IMPORTE END) IMPORTE  FROM CTA02 A
	INNER JOIN SUCURSAL B
	ON A.NRO_SUCURS = B.NRO_SUCURSAL
	WHERE (A.FECHA_EMIS BETWEEN '$desde' AND '$hasta')
	
	AND NRO_SUCURS IN (844, 845, 866, 907, 911, 913)
	GROUP BY NRO_SUCURSAL, B.DESC_SUCURSAL
	)A
	
	GROUP BY (CASE NUM WHEN 907 THEN 844 WHEN 911 THEN 845 WHEN 913 THEN 866 ELSE NUM END), 
	(CASE SUCURSAL WHEN 'SOFIA SALTA NOA' THEN 'SALTA NOA' WHEN 'SOFIA PORTAL SALTA' THEN 'PORTAL SALTA' WHEN 'SOFIA PASEO SALTA' THEN 'PASEO SALTA' ELSE SUCURSAL END)
	)A
	WHERE NUM LIKE '$suc'
	ORDER BY 1
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));


?>

<div class="container">
<table class="table table-striped" style="margin-left:50px; margin-right:50px">

        <tr >

				<td class="col-"><h4>NUM</h4></td>
		
				<td class="col-"><h4>SUCURSAL</h4></td>
		
                <td class="col-"><h4>IMPORTE</h4></td>

                
        </tr>

		
        <?php

       
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr >

				<td class="col-"><?php echo $v['NUM'] ;?></a></td>
		
                <td class="col-"><?php echo $v['SUCURSAL'] ;?></a></td>
				
				<td class="col-"><?php echo number_format($v['IMPORTE'] , 0, '', '.') ;?></a></td>

                

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
<table class="table table-striped" style="margin-left:50px; margin-right:50px">

        <tr >
		
				<td class="col-"><h4>NUM</h4></td>

				<td class="col-"><h4>SUCURSAL</h4></td>
		
                <td class="col-"><h4>IMPORTE</h4></td>

             

                

        </tr>


<?php

				



		

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];

$usuario = "sa";
$clave="Axoft";

$cid=odbc_connect($dsn, $usuario, $clave);

if (!$cid){
	exit("<strong>Ha ocurrido un error tratando de conectarse con el origen de datos.</strong>");
}



$sql=
	"
	
	SET DATEFORMAT YMD

	SELECT 
	CASE NUM WHEN 907 THEN 844 WHEN 911 THEN 845 WHEN 913 THEN 866 ELSE NUM END NUM,
	CASE SUCURSAL WHEN 'SOFIA SALTA NOA' THEN 'SALTA NOA' WHEN 'SOFIA PORTAL SALTA' THEN 'PORTAL SALTA' WHEN 'SOFIA PASEO SALTA' THEN 'PASEO SALTA' ELSE SUCURSAL END SUCURSAL,
	SUM(IMPORTE) IMPORTE
	FROM
	(
	SELECT NRO_SUCURSAL NUM, B.DESC_SUCURSAL SUCURSAL, SUM(CASE A.T_COMP WHEN 'NCR' THEN (A.IMPORTE*-1) ELSE A.IMPORTE END) IMPORTE  FROM CTA02 A
	INNER JOIN SUCURSAL B
	ON A.NRO_SUCURS = B.NRO_SUCURSAL
	WHERE (A.FECHA_EMIS BETWEEN '$desde' AND '$hasta')
	AND NRO_SUCURS LIKE '$suc'
	AND NRO_SUCURS IN (844, 845, 866, 907, 911, 913)
	GROUP BY NRO_SUCURSAL, B.DESC_SUCURSAL
	)A
	GROUP BY (CASE NUM WHEN 907 THEN 844 WHEN 911 THEN 845 WHEN 913 THEN 866 ELSE NUM END), 
	(CASE SUCURSAL WHEN 'SOFIA SALTA NOA' THEN 'SALTA NOA' WHEN 'SOFIA PORTAL SALTA' THEN 'PORTAL SALTA' WHEN 'SOFIA PASEO SALTA' THEN 'PASEO SALTA' ELSE SUCURSAL END)
	ORDER BY 1

	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));





?>



		
        <?php

		$sum = 0;
		
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr >

				<td class="col-"><?php echo $v['NUM'] ;?></a></td>
		
                <td class="col-"><?php echo $v['SUCURSAL'] ;?></a></td>
				
				<td class="col-"><?php echo number_format($v['IMPORTE'] , 0, '', '.') ;?></a></td>

				<?php $sum = $sum + $v['IMPORTE']; ?>

               

        </tr>

		
        <?php

        }

        ?>

		<tr>
		<td></td>
		<td align="center"><h3>TOTAL</h3></td>
		<td>
		<?php
		echo "<h3>".number_format($sum , 0, '', '.')."</h3>";
		?>
		</td>
		</tr>

		


<?php


echo '</table>	</div>';
}

}

?>
</body>