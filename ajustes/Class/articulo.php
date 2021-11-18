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

    public function traerArticulos(){

        $sql = " 
        
        SELECT COD_ARTICU, DESCRIPCIO FROM STA11 WHERE COD_ARTICU LIKE 'OU%' AND PERFIL != 'N'

        ";

        $rows = $this->retornarArray($sql);

        return $rows;

    }

}