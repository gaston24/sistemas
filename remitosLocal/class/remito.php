<?php

class Remito{

    function __construct(){

        require_once __DIR__.'/../../class/conexion.php';
        $this->conn = new Conexion;
        
    }
   
    public function traerRemitos($suc){

        include __DIR__.'\..\AccesoDatos\conn.php';
        
        $cid = $this->conn->conectar('central');
        
        $sql = "
        SET DATEFORMAT YMD
        SELECT N_COMP, NCOMP_IN_S, COD_PRO_CL
        FROM CTA09 WHERE FECHA_MOV >= GETDATE()-50 AND NRO_SUCURS = $suc AND N_COMP LIKE 'R%'
        ORDER BY N_COMP DESC
        ";
        $stmt = sqlsrv_query( $cid, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;

    }


    public function traerRemito($suc, $ncompInS){

        include __DIR__.'\..\AccesoDatos\conn.php';

        $cid = $this->conn->conectar('central');

        $sql = "
        SET DATEFORMAT YMD

        EXEC SJ_CONSULTA_REMITO_SUCURSAL $suc, '$ncompInS'
        ";
        $stmt = sqlsrv_query( $cid, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;

    }

}