<?php

class Pedido
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

        echo $numPed;
    
        return $numPed;
    }
    
    public function traerPedidos($codClient){

        $sql = "
        SET DATEFORMAT YMD
        SELECT FECHA, HORA, NRO_ORDEN, NRO_NOTA_PEDIDO, SUM(PRECIO_ESTIMADO) TOTAL, SUM(CANTIDAD) CANTIDAD 
        FROM RO_PEDIDO_PRECOMPRA WHERE COD_CLIENT LIKE '%$codClient'
        GROUP BY FECHA, HORA, COD_CLIENT, NRO_NOTA_PEDIDO, NRO_ORDEN
        ORDER BY FECHA DESC, HORA DESC
        "
        ;

        $rows = $this->retornarArray($sql);
            
        return $rows;

    }

    //$notaPedido = $_GET['notaPedido'];
   

    public function traerDetallePedido($notaPedido){

        $sql = "
        
        SELECT FECHA, HORA, NRO_ORDEN, COD_ARTICU, DESCRIPCIO, RUBRO, PRECIO_ESTIMADO, CANTIDAD FROM RO_PEDIDO_PRECOMPRA
		WHERE NRO_NOTA_PEDIDO = '$notaPedido'
        ";

        $rows = $this->retornarArray($sql);
            
        return $rows;

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

