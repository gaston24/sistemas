<?php

class Procedimiento{
    
    public function traerProcedimientos(){
        include __DIR__.'\..\AccesoDatos\conn.php';

        $sql = "
        SET DATEFORMAT YMD

        SELECT *
        FROM SJ_PROCEDIMIENTOS 
        ORDER BY NOMBRE_PROCEDIMIENTO
        ";

        $stmt = sqlsrv_query( $cid, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;

    }

}