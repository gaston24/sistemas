<?php
$fechaHora = date("Y") .  date("m") .  date("d") . (date("H")-5) . date("i") . date("s");
$nombreFecha = 'stock-extralarge-'.$fechaHora.'.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$nombreFecha);

$output = fopen('php://output', 'w');

$dsn = 'LOCALES';
$usuario = "sa";
$clave="Axoft";



$cid=odbc_connect($dsn, $usuario, $clave);

$sql=
	"
	
	SET DATEFORMAT YMD
	SELECT COD_ARTICU+';'+CAST(STOCK AS VARCHAR) FROM 
	(
		SELECT COD_ARTICU, CAST(SUM(CANT_STOCK) AS INT)STOCK 
		FROM STA19 WHERE COD_DEPOSI IN ('09') --AND CANT_STOCK > 0 
		GROUP BY COD_ARTICU
	)A

	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

while($v=odbc_fetch_array($result))fputcsv($output, $v);



?>