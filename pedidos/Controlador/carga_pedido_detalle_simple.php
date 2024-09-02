<?php



function pedido_detalle_simple(
    $deposito, //CODIGO DEPOSITO ARTICULO
    $numPed, //PEDIDO TANGO 
    $nroRenglon, //NRO_RENGLON
    $valor, //COD_ARTICU
    $cantArt, //CANT_PEDIDO
    $codClient, //COD_CLIENT 
    $talon_ped //TALON_PED
)
{
    require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
    $cid = new Conexion();
    $cid_central = $cid->conectar('central');
    // include 'dsn_central.php';

    $sqlCargaPedidoDetalle = "
    EXEC SJ_PEDIDOS_DETALLE_SIMPLE '$deposito', '$valor', $cantArt, $nroRenglon, $talon_ped, '$numPed', '$codClient'
    ";

    try {
        // odbc_exec($cid, $sqlCargaPedidoDetalle)or die(exit("Error en odbc_exec"));
        sqlsrv_query($cid_central, $sqlCargaPedidoDetalle)or die(exit("Error en odbc_exec"));
    } catch (\Throwable $th) {
        error_log("Error en carga_pedido_detalle_simple.php (sql)-> ".json_encode($th), 0);
        error_log("Error en carga_pedido_detalle_simple.php (variables)-> ".json_encode(['$deposito', '$valor', $cantArt, $nroRenglon, $talon_ped, '$numPed', '$codClient']), 0);
    }
    
    
}
