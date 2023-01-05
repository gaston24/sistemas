
<?php 

require_once __DIR__ .'/../../class/conexion.php' ;

$cid = new Conexion();
$cid_central = $cid->conectar('central');  


$ncomp = $_POST['ncomp'];


header('Content-Type: application/json');

$sql=
	"
	SET DATEFORMAT YMD

	SELECT * FROM SJ_CONTROL_AUDIRTORIA_CHAT WHERE NRO_REMITO = '$ncomp'
	";


ini_set('max_execution_time', 300);
$result=sqlsrv_query($cid_central,$sql)or die(exit("Error en odbc_exec"));

$chat = [];


while($v=sqlsrv_fetch_array($result)){
    $date=$v['DATE_TIME']->format('Y-m-d H:i:s');

    $chat[] = array(
        "remito" => $v['NRO_REMITO'], 
        "user" => $v['USER_CHAT'], 
        "message" => $v['MESSAGE'], 
        "datetime" => $date,
    );
}

echo json_encode($chat);
exit;