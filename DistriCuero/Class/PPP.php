
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
   
    public function detalleCuenta($codClient){

        $sql = "SELECT * FROM RO_PPP_FRANQUICIAS_PRECOMPRA WHERE COD_CLIENTE = '$codClient'
        ";

        $rows = $this->retornarArray($sql);

        $json = json_encode($rows);
            
        return $json;

    }
}