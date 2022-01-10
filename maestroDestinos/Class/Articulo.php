<?php

class Articulo
{
    
    private function retornarArray($sqlEnviado){

        require_once 'Conexion.php';

        $cid = new Conexion();
        $cid_central = $cid->conectar();  
        $sql = $sqlEnviado;

        $stmt = sqlsrv_query( $cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;  

    }

    public function traerArticulos($rubro, $temporada){

        $sql = " 
        
        SELECT A.COD_ARTICU, DESCRIPCION, DESTINO, TEMPORADA, B.RUBRO FROM MAESTRO_DESTINOS A
        LEFT JOIN SOF_RUBROS_TANGO B ON A.COD_ARTICU = B.COD_ARTICU
        WHERE TEMPORADA LIKE '$temporada' AND RUBRO LIKE '$rubro'

        ";

        $rows = $this->retornarArray($sql);

        return $rows;

    }    

}    