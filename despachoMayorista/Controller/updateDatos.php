
<?php

require_once '../Class/Conexion.php';

$cid = new Conexion();
$cid_central = $cid->conectar();  

$fecha= Date('Y-m-d');

date_default_timezone_set('Etc/GMT+3');
$Object = new DateTime();  
$hora = $Object->format("G:i");
         

    $sql = "UPDATE RO_PEDIDOS_MAYORISTA_ASIGNADOS SET TIPO_COMP = '$tipoComp', EMBALAJE = '$embalaje', DESPACHO = '$despacho', FECHA_DESPACHO = '$fechaDespacho', FECHA_MODIF = '$fecha',
    HORA_MODIF = '$hora' WHERE NRO_PEDIDO = '$pedido'";
    $stmt = sqlsrv_query( $cid_central, $sql );

?>