
<?php 
require_once __DIR__ .'/../../class/conexion.php' ;

$cid = new Conexion();
$cid = $cid->conectar('central');  

$ncomp = $_POST['ncomp'];

$user = $_POST['user'];
$msg = $_POST['msg'];


header('Content-Type: application/json');
$sql=
"
SET DATEFORMAT YMD

INSERT INTO SJ_CONTROL_AUDIRTORIA_CHAT (NRO_REMITO, USER_CHAT, MESSAGE)
VALUES ('$ncomp', '$user', '$msg')
";

ini_set('max_execution_time', 300);
$stmt = sqlsrv_prepare($cid,$sql)or die(exit("Error en sqlsrv_query"));
$stmt = sqlsrv_execute($stmt);



// var_dump(json_encode($chat));
echo json_encode('msg enviado');
exit;