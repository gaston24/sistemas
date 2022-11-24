<?php
require_once '../../Controlador/db.php';

$tiposPedidos = ['PEDIDO GENERAL', 'PEDIDO ACCESORIOS', 'PEDIDO OUTLET'];

if (isset($_GET['traerInfo'] )&& $_GET['traerInfo'] == 1) {
    $cliente=$_GET['cliente'];
    foreach ($tiposPedidos as $tipo) {

        $query = "SELECT LEYENDA_1 AS TIPO,COD_CLIENT,COUNT(NRO_PEDIDO) CANT_PEDIDOS FROM GVA21

        WHERE COD_CLIENT = '$cliente' AND FECHA_PEDI BETWEEN DATEADD(wk,DATEDIFF(wk,0,GETDATE()),0) AND DATEADD(wk,DATEDIFF(wk,0,GETDATE()),6) AND TALON_PED = '97' AND LEYENDA_1 = '$tipo'
        
        GROUP BY COD_CLIENT,LEYENDA_1";

        $stmt = sqlsrv_prepare($cid, $query);
        $result = sqlsrv_execute($stmt);

        $RESULT[] = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    sqlsrv_close($cid);
    echo json_encode($RESULT);
}
