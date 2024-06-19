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

    public function traerDetallePedidosCom($orden){

        $sql = "

            SELECT FECHA, NRO_NOTA_PEDIDO, COD_CLIENT, COD_ARTICU, DESCRIPCIO, CANTIDAD FROM RO_PEDIDO_PRECOMPRA
            WHERE NRO_ORDEN = '$orden'

        "
        ;

        $rows = $this->retornarArray($sql);
            
        return $rows;

    }
    
    public function traerPedidos($codClient){

        $sql = "SELECT A.FECHA FECHA_ORDEN, A.NRO_ORDEN, REPLACE(ISNULL(B.FECHA, ''),'1900-01-01','') FECHA_NP, B.HORA, B.COD_CLIENT, B.NRO_NOTA_PEDIDO, B.TOTAL, B.CANTIDAD,
                CASE WHEN C.NRO_ORDEN IS NOT NULL THEN 'RECHAZADA'
                     WHEN B.NRO_ORDEN IS NOT NULL THEN 'CARGADA'
                ELSE 'PENDIENTE'
                END ESTADO
                FROM RO_ORDENES_PRECOMPRA A
                LEFT JOIN 
                (
                    SELECT FECHA, HORA, COD_CLIENT, NRO_ORDEN, NRO_NOTA_PEDIDO, SUM(PRECIO_ESTIMADO) TOTAL, SUM(CANTIDAD) CANTIDAD 
                    FROM RO_PEDIDO_PRECOMPRA WHERE COD_CLIENT LIKE '%$codClient'
                    --AND NRO_ORDEN = '2000000180'
                    GROUP BY FECHA, HORA, COD_CLIENT, NRO_NOTA_PEDIDO, NRO_ORDEN
                ) B
                ON A.NRO_ORDEN = B.NRO_ORDEN
                LEFT JOIN (SELECT * FROM RO_ORDENES_RECHAZADAS WHERE COD_CLIENT LIKE '%$codClient') C ON A.NRO_ORDEN = C.NRO_ORDEN
                GROUP BY A.FECHA, A.NRO_ORDEN, B.FECHA, B.HORA, B.COD_CLIENT, B.NRO_NOTA_PEDIDO, B.TOTAL, B.CANTIDAD,
                CASE WHEN C.NRO_ORDEN IS NOT NULL THEN 'RECHAZADA'
                     WHEN B.NRO_ORDEN IS NOT NULL THEN 'CARGADA'
                ELSE 'PENDIENTE'
                END
                ORDER BY NRO_ORDEN DESC
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

