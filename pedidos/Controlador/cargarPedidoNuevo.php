<?php


require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
$cid = new Conexion();
$cid_central = $cid->conectar('central');

require 'fecha.php';


//////////// DECLARA VARIABLES

$suc = $_POST['numsuc'];
$codClient = $_POST['codClient'];
$t_ped = $_POST['tipo_pedido'];
$depo = $_POST['depo'];
$talon_ped = 97;
$stringParaSql = '(';

if( substr($codClient, 0, 2) == 'MA' ){
	$cantidadPedida = 6;
}else{
	$cantidadPedida = 8;
}


foreach ($_POST['matriz'] as $key => $value) {

	$stringParaSql .= '"'.$value[1].'","'.$value[$cantidadPedida].'","'.$value[3].'","'.$value[4].'";';
}

$stringParaSql = substr($stringParaSql, 0, -1);
$stringParaSql .= ')';

$sql = "EXEC FU_PEDIDOS $suc, '$codClient', '$t_ped', '$depo', $talon_ped, '$stringParaSql'";

sqlsrv_query($cid_central, $sql);

$sql = "SELECT TOP 1 NRO_PEDIDO
FROM GVA21 
WHERE COD_CLIENT = '$codClient' 
ORDER BY ID_GVA21 DESC;
";

$result = sqlsrv_query($cid_central, $sql);

$result = sqlsrv_fetch_array($result);

$result = $result['NRO_PEDIDO'];

echo $result;


?>
