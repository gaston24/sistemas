<?php

class Temporada
{

    private $cid;
    private $cid_central;

    
    function __construct()
    {

        require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
        $this->cid = new Conexion();
        
        $db = 'central';
        $this->cid_central = $this->cid->conectar($db);
    } 

    public function traerTemporadas(){

        $sql = "SELECT DISTINCT(NOMBRE_TEMP) TEMPORADA FROM MAESTRO_TEMPORADAS
                WHERE EXCLUIR IS NULL
                ORDER BY 1 DESC 
                
        ";
        $stmt = sqlsrv_query(  $this->cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;
    }

}  