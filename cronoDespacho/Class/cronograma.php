
<?php

class Cronograma
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

    public function traerCronograma($tipo){

        $sql = "SELECT * FROM RO_CRONOGRAMAS_DETALLE WHERE TIPO LIKE '$tipo' ORDER BY NRO_SUCURSAL";

        $rows = $this->retornarArray($sql);
    

        $myJSON = json_encode($rows);

        return $myJSON;
    

    }  

}    
