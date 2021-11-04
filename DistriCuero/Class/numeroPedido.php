<?php

class numero_Pedido
{


    public function traerNumPed(){

        require_once 'Conexion.php';

        $cid = new Conexion();
        $cid_central = $cid->conectar();        

        $sql = "
        
            SELECT CAST(MAX(NRO_NOTA_PEDIDO) AS FLOAT) NRO_NOTA_PEDID FROM RO_PEDIDO_PRECOMPRA

        ";
        $stmt = sqlsrv_query( $cid_central, $sql );

        $proxPed = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $proxPed[] = $v;
        }

        $numPed = ++$proxPed[0][0];
    
        return $numPed;
    }

}

class numero_Orden
{

    public function traerNumOrden(){

        require_once 'Conexion.php';

        $cid = new Conexion();
        $cid_central = $cid->conectar();        

        $sql = "
        
            SELECT CAST(MAX(NRO_ORDEN) AS FLOAT) NRO_NOTA_PEDID FROM RO_ORDENES_PRECOMPRA

        ";
        $stmt = sqlsrv_query( $cid_central, $sql );

        $proxOrd = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $proxOrd[] = $v;
        }

        $numOrd = ++$proxOrd[0][0];
        echo $numOrd;
    
        return $numOrd;
    }

}

