
<?php

class PPP
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
   
    public function detalleCuenta($cliente){

        $sql = "SELECT * FROM RO_PPP_FRANQUICIAS_PRECOMPRA WHERE COD_CLIENTE = '$cliente'
        ";

        $rows = $this->retornarArray($sql);
            
        return $rows;

    }
}