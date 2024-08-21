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

    public function buscarArticuloPorCodigo($codArticulo)
    {
        $sql = "SELECT A.COD_ARTICU, A.DESCRIPCION, A.DESTINO, A.TEMPORADA, B.RUBRO, A.FECHA_MOD, A.LIQUIDACION, C.PRECIO
                FROM MAESTRO_DESTINOS A
                LEFT JOIN SOF_RUBROS_TANGO B ON A.COD_ARTICU = B.COD_ARTICU
                LEFT JOIN (SELECT COD_ARTICU, PRECIO FROM GVA17 WHERE NRO_DE_LIS = 20) C ON A.COD_ARTICU = C.COD_ARTICU
                WHERE A.COD_ARTICU = ?";

        $stmt = sqlsrv_prepare($this->cid_central, $sql, array($codArticulo));
        
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . print_r(sqlsrv_errors(), true));
        }

        $result = sqlsrv_execute($stmt);
        if ($result === false) {
            throw new Exception("Error executing statement: " . print_r(sqlsrv_errors(), true));
        }

        $rows = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }
}