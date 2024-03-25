<?php

class Articulo
{
    

    private $cid;
    private $cid_central;


    function __construct()
    {

        require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
        
        $this->cid = new Conexion();

        if (session_status() === PHP_SESSION_NONE) {
           
            session_start();

        }
        
        $db = (isset($_SESSION['usuarioUy']) && $_SESSION['usuarioUy'] == 1) ? 'uy' : 'central';

        $this->cid_central = $this->cid->conectar($db);

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

    public function traerArticulos($rubro, $temporada, $liquidacion){


        $sql = " SELECT A.COD_ARTICU, DESCRIPCION, DESTINO, TEMPORADA, B.RUBRO,A.FECHA_MOD, LIQUIDACION FROM MAESTRO_DESTINOS A
                 LEFT JOIN SOF_RUBROS_TANGO B ON A.COD_ARTICU = B.COD_ARTICU
                 WHERE TEMPORADA LIKE '$temporada' AND RUBRO LIKE '$rubro' 
        ";

        if($liquidacion != '%'){
            $sql .= "AND LIQUIDACION LIKE '$liquidacion'";
        }
       

        $rows = $this->retornarArray($sql);

        return $rows;

    }   
    
    public function traerNovedades(){


        $sql = " 
        SELECT A.COD_ARTICU, DESCRIPCION, DESTINO, TEMPORADA, B.RUBRO,A.FECHA_MOD FROM MAESTRO_DESTINOS A
        LEFT JOIN SOF_RUBROS_TANGO B ON A.COD_ARTICU = B.COD_ARTICU
        WHERE A.FECHA_MOD = (
        SELECT  MAX(FECHA_MOD) AS FECHA_MOD
        FROM MAESTRO_DESTINOS)
        ";

        $rows = $this->retornarArray($sql);

        return $rows;

    }    

}    