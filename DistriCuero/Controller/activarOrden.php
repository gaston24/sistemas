<?php

$fecha= Date('Y-m-d');

$matriz = $_POST['matriz'];



foreach($matriz as $orden){
  require_once '../Class/Conexion.php';

    $cid = new Conexion();
    $cid_central = $cid->conectar();      

    $sql = "

    UPDATE RO_ORDENES_PRECOMPRA SET ACTIVA = 1, FECHA_MODIF = '$fecha' WHERE NRO_ORDEN = '$orden'

    ";
    $stmt = sqlsrv_query( $cid_central, $sql );

}

echo $orden;

?>
