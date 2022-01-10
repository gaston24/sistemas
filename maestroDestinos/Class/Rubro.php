<?php

class Rubro
{

    public function traerRubros(){
        try {

            $servidor_central = 'servidor';
            $conexion_central = array( "Database"=>"LAKER_SA", "UID"=>"sa", "PWD"=>"Axoft1988", "CharacterSet" => "UTF-8");
            $cid_central = sqlsrv_connect($servidor_central, $conexion_central);
             
         } catch (PDOException $e){
                 echo $e->getMessage();
         }

        $sql = "
        SELECT REPLACE(DESCRIP, '_', '') DESCRIP FROM STA11FLD WHERE DESCRIP NOT LIKE '[_][ZD]%' AND DESCRIP NOT LIKE 'Todos' 
        AND DESCRIP NOT LIKE '%OUTLET' AND DESCRIP NOT LIKE '%VINILICO' AND DESCRIP NOT IN ('ALHAJEROS','PACKAGING','_KITS')
        ";
        $stmt = sqlsrv_query( $cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;
    }

}  