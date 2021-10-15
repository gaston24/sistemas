
<?php 

$dsn = '1 - CENTRAL';
$usuario = "sa";
$clave="Axoft1988";

$ncomp = $_POST['ncomp'];
$user = $_POST['user'];
$msg = $_POST['msg'];

$cid=odbc_connect($dsn, $usuario, $clave);

header('Content-Type: application/json');

$sql=
	"
	SET DATEFORMAT YMD

	INSERT INTO SJ_CONTROL_AUDIRTORIA_CHAT (NRO_REMITO, USER_CHAT, MESSAGE)
    VALUES ('$ncomp', '$user', '$msg')
	";

ini_set('max_execution_time', 300);
odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));


// var_dump(json_encode($chat));
echo json_encode('msg enviado');
exit;