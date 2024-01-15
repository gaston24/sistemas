<?php

class Articulo
{
    
    private $cid;
    private $cid_central;

    
    function __construct()
    {

        require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
        $this->cid = new Conexion();
        $this->cid_central = $this->cid->conectar('central');

    } 


    private function retornarArray($sqlEnviado){

        $sql = $sqlEnviado;

        $stmt = sqlsrv_query( $this->cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;  

    }

    public function traerArticulos($rubro, $temporada){

        $sql = " 
        
        SELECT A.COD_ARTICU, DESCRIPCION, DESTINO, TEMPORADA, B.RUBRO,A.FECHA_MOD FROM MAESTRO_DESTINOS A
        LEFT JOIN SOF_RUBROS_TANGO B ON A.COD_ARTICU = B.COD_ARTICU
        WHERE TEMPORADA LIKE '$temporada' AND RUBRO LIKE '$rubro'

        ";

        $rows = $this->retornarArray($sql);

        return $rows;

    }    

}    