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

$desde = $_GET['desde'].' 00:00:00.000';
$hasta = $_GET['hasta'].' 23:59:59.000';


$cid=odbc_connect($dsn, $usuario, $clave);

$sql=
	"
	
SET DATEFORMAT YMD
	SELECT CAST( FECHA AS VARCHAR) COLLATE Latin1_General_BIN+';'+ COD_ARTICU+';'+ CAST(CANT AS VARCHAR)+';'+ RUBRO+';'+ DESCRIPCIO+';'+
	CAST(CASE WHEN CANT_STOCK IS NULL THEN 0 ELSE CANT_STOCK END AS VARCHAR) CANT_STOCK
	FROM
	(
	SELECT CAST(A.FECHA AS DATE)FECHA, A.COD_ARTICU, A.CANT, A.RUBRO, B.DESCRIPCIO, CAST(C.CANT_STOCK AS INT)CANT_STOCK
	FROM SOF_INVENTARIO_HISTORICO A
	INNER JOIN STA11 B
	ON A.COD_ARTICU = B.COD_ARTICU
	LEFT JOIN STA19 C
	ON A.COD_ARTICU = C.COD_ARTICU
	WHERE FECHA BETWEEN '$desde' AND '$hasta'
	AND A.USUARIO = '$user'
	)A
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

while($v=odbc_fetch_array($result))fputcsv($output, $v);


}
?>