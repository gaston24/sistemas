
<?php

$fecha= Date('Y-m-d');
   
date_default_timezone_set('Etc/GMT+3');
$Object = new DateTime();  
$hora = $Object->format("G:i");

$orden = $_POST['orden'];


  require_once '../Class/Conexion.php';

    $cid = new Conexion();
    $cid_central = $cid->conectar();   
    
    $sql = "

    UPDATE SJ_LOCAL_ENTREGA_TABLE SET ENTREGADO = 1, FECHA_ENTREGADO = '$fecha', HORA_ENTREGA = '$hora' 
    WHERE NRO_ORDEN_ECOMMERCE =  '$orden' AND ENTREGADO = 0

    ";

    $stmt = sqlsrv_query( $cid_central, $sql );


echo $orden;

?>
