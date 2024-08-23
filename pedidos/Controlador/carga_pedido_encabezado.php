<?php



function pedido_encabezado ($codClient, $depo, $fecha, $t_ped, $numPed, $talon_ped)
{
    // include 'dsn_central.php';

    require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
    $cid = new Conexion();
    $cid_central = $cid->conectar('central');

    $sqlCargaPedidoEncabezado = "
    SET DATEFORMAT YMD
    INSERT INTO GVA21
    (
    CIRCUITO, COD_CLIENT, COD_SUCURS, 
    COD_TRANSP, COD_VENDED, COMP_STK, 
    COND_VTA, COTIZ, ESTADO, 
    EXPORTADO, FECHA_APRU, FECHA_ENTR, 
    FECHA_PEDI, LEYENDA_1, MON_CTE, 
    N_LISTA, N_REMITO, NRO_PEDIDO, 
    NRO_SUCURS, ORIGEN, PORC_DESC, 
    REVISO_FAC, REVISO_PRE, REVISO_STK, 
    TALONARIO, TALON_PED, TOTAL_PEDI, 
    TIPO_ASIEN, ID_ASIENTO_MODELO_GV, TAL_PE_ORI, 
    FECHA_INGRESO, FECHA_ULTIMA_MODIFICACION, ID_DIRECCION_ENTREGA, 
    ES_PEDIDO_WEB, FECHA_O_COMP, WEB_ORDER_ID, 
    TOTAL_DESC_TIENDA, PORCEN_DESC_TIENDA, 
    HORA_INGRESO
    )
    VALUES
    (
    1, '$codClient', '$depo', 
    (SELECT COD_TRANSP FROM GVA14 WHERE COD_CLIENT = '$codClient'), (SELECT COD_VENDED FROM GVA14 WHERE COD_CLIENT = '$codClient'), 1, 
    (SELECT COND_VTA FROM GVA14 WHERE COD_CLIENT = '$codClient'), 1, 2, 
    0, '1800-01-01', '1800-01-01', 
    '$fecha', 'PEDIDO $t_ped', 1, 
    (SELECT NRO_LISTA FROM GVA14 WHERE COD_CLIENT = '$codClient'), ' 000000000000', ' '+'$numPed', 
    0, 'E', (SELECT PORC_DESC FROM GVA14 WHERE COD_CLIENT = '$codClient'), 
    'A', 'A', 'A', 
    0, $talon_ped, 0, 
    '', 3, 0,
    '1800-01-01', '1800-01-01', (SELECT TOP 1 ID_DIRECCION_ENTREGA FROM DIRECCION_ENTREGA WHERE COD_CLIENTE = '$codClient'), 
    0, '1800-01-01', 0, 
    0, 0, 
    (SELECT LEFT((CAST((CONVERT(TIME, GETDATE()  )) AS VARCHAR(8))), 2)+SUBSTRING((CAST((CONVERT(TIME, GETDATE()  )) AS VARCHAR(8))), 4, 2)+RIGHT((CAST((CONVERT(TIME, GETDATE()  )) AS VARCHAR(8))), 2))
    )	
    ";


    sqlsrv_query($cid_central,$sqlCargaPedidoEncabezado)or die(exit("Error en odbc_exec"));

}

?>