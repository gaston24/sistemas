
<?php

class TipoCrono
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
    
    public function traerTipo(){

        $sql = "SELECT * FROM RO_TIPO_CRONOGRAMA";

        $rows = $this->retornarArray($sql);

        return $rows;

    }

}    


$a=new TipoCrono();

$a->traerTipo();