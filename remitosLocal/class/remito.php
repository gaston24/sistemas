<?php

class Remito{

    public function traerRemitos($suc){
        include __DIR__.'\..\AccesoDatos\conn.php';

        $sql = "
        SET DATEFORMAT YMD
        SELECT N_COMP, NCOMP_IN_S, COD_PRO_CL
        FROM CTA09 WHERE FECHA_MOV >= GETDATE()-30 AND NRO_SUCURS = $suc AND N_COMP LIKE 'R%'
        ORDER BY N_COMP DESC
        ";
        $stmt = sqlsrv_query( $cid_locales, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;

    }


    public function traerRemito($suc, $ncompInS){
        include __DIR__.'\..\AccesoDatos\conn.php';

        $sql = "
        SET DATEFORMAT YMD

        EXEC SJ_CONSULTA_REMITO_SUCURSAL $suc, '$ncompInS'
        ";
        $stmt = sqlsrv_query( $cid_locales, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;

    }

}