<?php
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
$fechaHora = date("Y") .  date("m") .  date("d") . (date("H")-5) . date("i") . date("s");
$nombreFecha = 'inventario - '.$fechaHora.'.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$nombreFecha);

$output = fopen('php://output', 'w');

$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave="Axoft1988";

$user = $_SESSION['username'];
$rubro = $_GET['rubro'];

$desde = $_GET['desde'].' 00:00:00.000';
$hasta = $_GET['hasta'].' 23:59:59.000';


$cid=odbc_connect($dsn, $usuario, $clave);

$sql=
	"
	
SET DATEFORMAT YMD
	
	SELECT FECHA  COLLATE Latin1_General_BIN +';'+USUARIO+';'+RUBRO+';'+CANT+';'+STOCK+';'+DIF FROM
	(
		SELECT CAST(CAST(FECHA AS DATE) AS VARCHAR) FECHA, CAST(USUARIO AS VARCHAR)USUARIO, CAST(RUBRO AS VARCHAR)RUBRO, 
		CAST(CANT AS VARCHAR)CANT, CAST(STOCK AS VARCHAR)STOCK, CAST(DIF AS VARCHAR)DIF 
		FROM SOF_INVENTARIO_RUBRO 
		WHERE (FECHA BETWEEN '$desde' AND '$hasta') AND RUBRO LIKE '%$rubro%'  
	)A
	
	
	
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

while($v=odbc_fetch_array($result))fputcsv($output, $v);


}
?>