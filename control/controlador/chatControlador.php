
<?php 

$dsn = '1 - CENTRAL';
$usuario = "sa";
$clave="Axoft1988";

$ncomp = $_POST['ncomp'];

$cid=odbc_connect($dsn, $usuario, $clave);

header('Content-Type: application/json');

$sql=
	"
	SET DATEFORMAT YMD

	SELECT * FROM SJ_CONTROL_AUDIRTORIA_CHAT WHERE NRO_REMITO = '$ncomp'
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

$chat = [];

while($v=odbc_fetch_array($result)){
    $date=date_create($v['DATE_TIME']);

    $chat[] = array(
        "remito" => $v['NRO_REMITO'], 
        "user" => $v['USER_CHAT'], 
        "message" => $v['MESSAGE'], 
        "datetime" => date_format($date,"Y/m/d H:i:s"),
    );
}

echo json_encode($chat);
exit;