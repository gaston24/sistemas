<?php

class Destino
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
            SELECT A.* FROM MAESTRO_DESTINOS A
            LEFT JOIN MAESTRO_TEMPORADAS B ON A.TEMPORADA = B.NOMBRE_TEMP
            WHERE EXCLUIR IS NULL
            AND TEMPORADA LIKE '$temporada' AND FAMILIA LIKE '$rubro'

        ";

        $rows = $this->retornarArray($sql);

        return $rows;

    }  

    public function liquidar($codArticuString, $liquidacion ){

        $sql = " UPDATE MAESTRO_DESTINOS SET LIQUIDACION = '$liquidacion' WHERE COD_ARTICU IN ($codArticuString)";

        $stmt = sqlsrv_query( $this->cid_central, $sql );

        return $rows;

    }  

    public function cambiarDestino ($codArticuString, $destino ){

        $sql = " UPDATE MAESTRO_DESTINOS SET DESTINO = '$destino' WHERE COD_ARTICU = '$codArticuString'";

        $stmt = sqlsrv_query( $this->cid_central, $sql );

        return true;;

    }

    public function cambiarLiquidacion ($codArticuString, $liquidacion ){

        $sql = " UPDATE MAESTRO_DESTINOS SET LIQUIDACION = '$liquidacion' WHERE COD_ARTICU = '$codArticuString'";

        $stmt = sqlsrv_query( $this->cid_central, $sql );

        return true;;

    }
      

}    
