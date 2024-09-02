<?php


function pedido_detalle_simple_kit(
    $deposito, //CODIGO DEPOSITO ARTICULO
    $numPed, //PEDIDO TANGO 
    $nroRenglon, //NRO_RENGLON
    $ultRenglon, //ULTIMO RENGLON
    $valor, //COD_ARTICU
    $codArt, //COD_ARTICU
    $cantArt, //CANT_PEDIDO
    $codClient, //COD_CLIENT 
    $talon_ped //TALON_PED
)
{
    require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
    $cid = new Conexion();
    $cid_central = $cid->conectar('central');

    $cantArt = (int)$cantArt;


    $sqlCargaPedidoDetalle = "
    EXEC SJ_PEDIDOS_DETALLE_SIMPLE_KIT '$deposito', '$valor', '$codArt', $cantArt, $nroRenglon, $ultRenglon, $talon_ped, '$numPed', '$codClient'
    ";


   
    sqlsrv_query($cid_central, $sqlCargaPedidoDetalle)or die(exit("Error en odbc_exec"));
}

function pedido_detalle_simple_kit_encabezado(
    $deposito, //CODIGO DEPOSITO ARTICULO
    $numPed, //PEDIDO TANGO 
    $nroRenglon, //NRO_RENGLON
    $valor, //COD_ARTICU
    $cantArt, //CANT_PEDIDO
    $codClient, //COD_CLIENT 
    $talon_ped //TALON_PED
)
{
    // include 'dsn_central.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
    $cid = new Conexion();
    $cid_central = $cid->conectar('central');

    $sqlCargaPedidoDetalle = "
    EXEC SJ_PEDIDOS_DETALLE_SIMPLE_KIT_ENCABEZADO '$deposito', '$numPed', $nroRenglon, '$valor', $cantArt,  '$codClient',  $talon_ped
    ";
   
 
    sqlsrv_query($cid_central, $sqlCargaPedidoDetalle)or die(exit("Error en odbc_exec"));
}



?>