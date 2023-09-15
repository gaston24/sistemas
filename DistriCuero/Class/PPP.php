
<?php

class PPP
{
    private $conn;
    
    function __construct(){

        require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
        $this->conn = new Conexion;
        
    }


    private function retornarArray($sqlEnviado){
    
        $cid_central = $this->conn->conectar('central');

        $sql = $sqlEnviado;

        $stmt = sqlsrv_query( $cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;  

    }
   
    public function detalleCuenta($codClient){

        $sql = "SELECT * FROM RO_PPP_FRANQUICIAS_PRECOMPRA WHERE COD_CLIENTE = '$codClient'
        ";

        $rows = $this->retornarArray($sql);

        $json = json_encode($rows);
            
        return $json;

    }
}