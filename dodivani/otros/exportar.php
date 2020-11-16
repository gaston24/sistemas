<?php

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";
$cid = odbc_connect($dsn, $user, $pass);

$sql1="TRUNCATE TABLE SJ_DODIVANI_EXPORTAR";
		
odbc_exec($cid,$sql1)or die(exit("Error en odbc_exec"));


foreach($_POST['codArticu'] as $x => $a){
	
	$codArticu = $_POST['codArticu'][$x];
	$cant = $_POST['cant'][$x];
	$local = $_POST['local'][$x];
	
	if($local != ''){
		//echo $codArticu.' '.$cant.' '.$local.'<br>';
		
		$sql2="

		SET DATEFORMAT YMD
		
		INSERT INTO SJ_DODIVANI_EXPORTAR (COD_ARTICU, CANT, SUCURSAL)
		VALUES('$codArticu', $cant, '$local')
		
		";
		
		odbc_exec($cid,$sql2)or die(exit("Error en odbc_exec"));
	}
}








$fechaHora = date("Y") .  date("m") .  date("d") . (date("H")-5) . date("i") . date("s");
$nombreFecha = 'stock-extralarge-'.$fechaHora.'.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$nombreFecha);

$output = fopen('php://output', 'w');

$dsn = '1 - CENTRAL';
$usuario = "sa";
$clave="Axoft1988";

$cid=odbc_connect($dsn, $usuario, $clave);

$sql=
	"
	
	SET DATEFORMAT YMD
	EXEC SJ_ROTACIONES_DODIVANI_EXPORTAR
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

while($v=odbc_fetch_array($result))fputcsv($output, $v);





//header("Location:../index.php");

?>