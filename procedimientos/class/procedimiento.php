<?php

class Procedimiento{

    function __construct(){

        require_once __DIR__.'/../../class/conexion.php';
        $this->conn = new Conexion;      
    }
    
    public function traerProcedimientos(){
        include __DIR__.'\..\AccesoDatos\conn.php';
        $cid = $this->conn->conectar('central');  
        $sql = "SET DATEFORMAT YMD 
        SELECT * FROM SJ_PROCEDIMIENTOS 
        ORDER BY NOMBRE_PROCEDIMIENTO";

        $stmt = sqlsrv_query( $cid, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;

    }

}