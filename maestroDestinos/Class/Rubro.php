<?php

class Rubro
{

    private $cid;
    private $cid_central;

    
    function __construct()
    {

        require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
        $this->cid = new Conexion();
        $this->cid_central = $this->cid->conectar('central');

    } 


    public function traerRubros(){

        $sql = "
        SELECT REPLACE(DESCRIP, '_', '') DESCRIP FROM STA11FLD WHERE DESCRIP NOT LIKE '[_][ZD]%' AND DESCRIP NOT LIKE 'Todos' 
        AND DESCRIP NOT LIKE '%OUTLET' AND DESCRIP NOT IN ('ALHAJEROS','PACKAGING','_KITS')
        ";
        $stmt = sqlsrv_query( $this->cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;
    }
    public function traerRubrosDestinos(){

        $sql="SELECT DISTINCT(RUBRO) RUBRO FROM SOF_RUBROS_TANGO
        WHERE RUBRO NOT LIKE '[_]%' AND RUBRO NOT LIKE '%OUTLET' AND RUBRO NOT IN ('ALHAJEROS','PACKAGING')";
        
        $stmt = sqlsrv_query( $this->cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;
    }

}  