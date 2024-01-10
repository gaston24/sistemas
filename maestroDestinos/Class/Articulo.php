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

        $sql = "SELECT A.* FROM MAESTRO_DESTINOS A
                LEFT JOIN MAESTRO_TEMPORADAS B ON A.TEMPORADA = B.NOMBRE_TEMP
                WHERE EXCLUIR IS NULL AND TEMPORADA LIKE '$temporada' AND RUBRO LIKE '$rubro'

        ";

        $rows = $this->retornarArray($sql);

        return $rows;

    }    

}    