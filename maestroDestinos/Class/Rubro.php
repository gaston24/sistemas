<?php

class Rubro
{

    private $cid;
    private $cid_central;

    
    function __construct()
    {

        require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
        $this->cid = new Conexion();

        $db = (isset($_SESSION['usuarioUy']) && $_SESSION['usuarioUy'] == 1) ? 'uy' : 'central';

        $this->cid_central = $this->cid->conectar($db);

    } 


    public function traerRubros(){

        $sql = "SELECT DISTINCT(RUBRO) RUBRO FROM SOF_RUBROS_TANGO 

        WHERE RUBRO NOT LIKE '[_]%' AND RUBRO NOT LIKE '%OUTLET' AND RUBRO NOT IN ('ALHAJEROS','PACKAGING')

        ";
        $stmt = sqlsrv_query( $this->cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;
    }

}  