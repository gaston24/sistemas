<?php

class Remito{

    function __construct() 
    {
        
        require_once $_SERVER['DOCUMENT_ROOT'] .'/sistemas/class/conexion.php';
        
        $conn = new Conexion();
        $this->cid = $conn->conectar('central');
        $this->cidLakerbis = $conn->conectar('locales');
        $this->cidLocal = $conn->conectar('local');
        

    }
    
   
    public function traerRemitos($suc){

        
        $sql = "
        SET DATEFORMAT YMD
        SELECT N_COMP, NCOMP_IN_S, COD_PRO_CL
        FROM STA14 WHERE FECHA_MOV >= GETDATE()-30 AND N_COMP LIKE 'R%'
        ORDER BY N_COMP DESC
        ";
        
        $stmt = sqlsrv_query( $this->cidLocal, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;

    }


    public function traerRemito($suc, $ncompInS){

       /*  $cid = $this->conn->conectar('central'); */

        $sql = "
        SET DATEFORMAT YMD

        EXEC SJ_CONSULTA_REMITO_SUCURSAL $suc, '$ncompInS'
        ";
       
        $stmt = sqlsrv_query( $this->cidLakerbis, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;

    }

}