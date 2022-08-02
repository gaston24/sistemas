
<?php

$numSuc = $_SESSION['numsuc'];

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
    
    public function traerPedidosPendientes($numSuc){

        $sql = "SET DATEFORMAT YMD

                SELECT ORIGEN, NRO_ORDEN_ECOMMERCE, RAZON_SOCIAL, N_COMP, LOCAL_ENTREGA, GC_GDT_NUM_GUIA, FECHA, N_IMPUESTO, ENTREGADO, FECHA_ENTREGADO, HORA_ENTREGA, FECHA_PEDIDO, PEDIDO, DEPOSITO, METODO_ENVIO FROM SJ_LOCAL_ENTREGA_TABLE
                WHERE N_IMPUESTO = '$numSuc' AND FECHA_ENTREGADO IS NULL
                GROUP BY ORIGEN, NRO_ORDEN_ECOMMERCE, RAZON_SOCIAL, N_COMP, LOCAL_ENTREGA, GC_GDT_NUM_GUIA, FECHA, N_IMPUESTO, ENTREGADO, FECHA_ENTREGADO, HORA_ENTREGA, FECHA_PEDIDO, PEDIDO, DEPOSITO, METODO_ENVIO
                ORDER BY ENTREGADO ASC, N_COMP
        "
        ;

        $rows = $this->retornarArray($sql);
            
        return $rows;

    }

}