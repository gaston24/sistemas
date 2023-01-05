<?php


$codClient = $_POST['codClient'];
$NroOrden = $_POST['NroOrden'];
// var_dump($area);

  require_once '../Class/conexion.php';

    $cid = new Conexion();
    $cid_central = $cid->conectar();        

    $sql = "DELETE FROM RO_ORDENES_RECHAZADAS WHERE NRO_ORDEN = '$NroOrden' AND COD_CLIENT = '$codClient'
    ";

    $stmt = sqlsrv_query( $cid_central, $sql );

echo $NroOrden;

?>