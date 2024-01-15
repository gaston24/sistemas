<?php

class Temporada
{

    private $cid;
    private $cid_central;

    
    function __construct()
    {

        require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
        $this->cid = new Conexion();
        $this->cid_central = $this->cid->conectar('central');

    } 

    public function traerTemporadas(){

        $sql = "

        SELECT DISTINCT(TEMPORADA) TEMPORADA FROM MAESTRO_DESTINOS WHERE TEMPORADA NOT IN ('SIN','ELIMINAR/DESHABILITA') AND TEMPORADA IS NOT NULL
        ORDER BY 1 DESC 
        
        ";
        $stmt = sqlsrv_query(  $this->cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;
    }
    
    public function traerTemporadasDestinos (){

        $sql ="SELECT DISTINCT(NOMBRE_TEMP) TEMPORADA FROM MAESTRO_TEMPORADAS
        ORDER BY 1 DESC";

        $stmt = sqlsrv_query(  $this->cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;

    }

}  