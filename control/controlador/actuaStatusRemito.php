
<?php 

$dsn = '1 - CENTRAL';
$usuario = "sa";
$clave="Axoft1988";

$ncomp = $_POST['ncomp'];
$status = $_POST['status'];


$cid=odbc_connect($dsn, $usuario, $clave);

header('Content-Type: application/json');

$sql=
	"
	SET DATEFORMAT YMD

	UPDATE SJ_CONTROL_AUDITORIA SET OBSERVAC_LOGISTICA = '$status' WHERE NRO_REMITO = '$ncomp'
	";

ini_set('max_execution_time', 300);
odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));


// var_dump(json_encode($chat));
echo json_encode('status modificado');
exit;