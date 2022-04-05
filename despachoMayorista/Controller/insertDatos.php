
<?php

require_once '../Class/Conexion.php';

$cid = new Conexion();
    $cid_central = $cid->conectar();  

$fecha= Date('Y-m-d');

date_default_timezone_set('Etc/GMT+3');
$Object = new DateTime();  
$hora = $Object->format("G:i");
         

    $sql = "
    INSERT INTO DBO.RO_PEDIDOS_MAYORISTA_ASIGNADOS (FECHA, ESTADO, HORA_INGRESO, COD_CLIENT, RAZON_SOCI, LOCALIDAD, TALON_PED, NRO_PEDIDO, CANT_PEDIDO, CANT_PENDIENTE, IMP_PENDIENTE, 
    COD_VENDED, VENDEDOR, TIPO_COMP, EMBALAJE, DESPACHO, FECHA_DESPACHO)
    VALUES ('$fechaPed', $estado,'$HoraPed','$codClient','$cliente','$localidad', $talonPed, $nroPedido, $cantPedid, $cantPend, $importe, '$codVended', '$vendedor','$tipoComp',
    '$embalaje','$despacho','$fechaDespacho')

    ";
    $stmt = sqlsrv_query( $cid_central, $sql );

?>