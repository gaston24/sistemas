<?php

class Remito
{

    public function traerRemitos(){
        try {

            $servidor_locales = 'LAKERBIS';
            $conexion_locales = array( "Database"=>"LOCALES_LAKERS", "UID"=>"sa", "PWD"=>"Axoft", "CharacterSet" => "UTF-8");
            $cid_locales = sqlsrv_connect($servidor_locales, $conexion_locales);
             
         } catch (PDOException $e){
                 echo $e->getMessage();
         }

        $sql = "
            SET DATEFORMAT YMD
            SELECT B.DESC_SUCURSAL, N_COMP, CAST(FECHA_MOV AS DATE) FECHA, COD_PRO_CL, NCOMP_IN_S FROM CTA09 A
            LEFT JOIN SUCURSALES_LAKERS B ON A.NRO_SUCURS = B.NRO_SUCURSAL
            WHERE FECHA_MOV >= GETDATE()-45 AND NRO_SUCURS = '2' AND N_COMP LIKE 'R%' AND ESTADO_MOV != 'A'
            ORDER BY N_COMP DESC
        ";
        $stmt = sqlsrv_query( $cid_locales, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;
    }

}    