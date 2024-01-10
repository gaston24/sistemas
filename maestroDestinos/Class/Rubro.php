<?php

class Rubro
{

    public function traerRubros(){
        try {

            include_once __DIR__.'/../class/conexion.php';
            $conn = new Conexion;
            
            $cid_central = $conn->conectar('central');


            // $servidor_central = 'servidor';
            // $conexion_central = array( "Database"=>"LAKER_SA", "UID"=>"sa", "PWD"=>"Axoft1988", "CharacterSet" => "UTF-8");
            // $cid_central = sqlsrv_connect($servidor_central, $conexion_central);
             
         } catch (PDOException $e){
                 echo $e->getMessage();
         }

        $sql = "SELECT DISTINCT(RUBRO) RUBRO FROM SOF_RUBROS_TANGO 
                WHERE RUBRO NOT LIKE '[_]%' AND RUBRO NOT LIKE '%OUTLET' AND RUBRO NOT IN ('ALHAJEROS','PACKAGING')
        ";
        $stmt = sqlsrv_query( $cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;
    }

}  