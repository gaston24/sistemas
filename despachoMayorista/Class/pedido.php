
<?php

class Pedido
{

    private function retornarArray($sqlEnviado)
    {

        require_once 'Conexion.php';

        $cid = new Conexion();
        $cid_central = $cid->conectar();
        $sql = $sqlEnviado;

        $stmt = sqlsrv_query($cid_central, $sql);

        $rows = array();

        while ($v = sqlsrv_fetch_array($stmt)) {
            $rows[] = $v;
        }


        return $rows;
    }


    public function traerPedidos($vendedor)
    {

        $sql = "SELECT COD_VENDED, COD_CLIENT, RAZON_SOCI, LOCALIDAD, SUM(CANT_PEDIDO) UNID_PEDIDO, SUM(CANT_PENDIENTE) UNID_PENDIENTE, COUNT(NRO_PEDIDO) CANT_PEDIDOS, SUM(IMP_PENDIENTE) IMPORTE FROM RO_PEDIDOS_PENDIENTE_MAYORISTAS
                WHERE VENDEDOR LIKE '$vendedor'
                GROUP BY  COD_VENDED, COD_CLIENT, LOCALIDAD, RAZON_SOCI";

        $rows = $this->retornarArray($sql);
        
        $myJSON = json_encode($rows);

        return $myJSON;

    }

    public function traerPedidosCliente($cliente)
    {

        $sql = "SELECT FECHA, ESTADO, HORA_INGRESO, COD_CLIENT, RAZON_SOCI, LOCALIDAD, TALON_PED, NRO_PEDIDO, CANT_PEDIDO, CANT_PENDIENTE, IMP_PENDIENTE, COD_VENDED, VENDEDOR, TIPO_COMP, EMBALAJE, DESPACHO, ARREGLO, PRIORIDAD,
                CAST(FECHA_DESPACHO AS DATE) FECHA_DESPACHO FROM
                (
                SELECT FECHA, ESTADO, HORA_INGRESO, COD_CLIENT, RAZON_SOCI, LOCALIDAD, TALON_PED, NRO_PEDIDO, CANT_PEDIDO, CANT_PENDIENTE, IMP_PENDIENTE, COD_VENDED, VENDEDOR, TIPO_COMP, EMBALAJE, DESPACHO, ARREGLO, PRIORIDAD, FECHA_DESPACHO FROM RO_PEDIDOS_MAYORISTA_ASIGNADOS
            
                UNION ALL
            
                SELECT FECHA, ESTADO, HORA_INGRESO, COD_CLIENT, RAZON_SOCI, LOCALIDAD, TALON_PED, NRO_PEDIDO, CANT_PEDIDO, CANT_PENDIENTE, IMP_PENDIENTE, COD_VENDED, VENDEDOR, '' TIPO_COMP, '' EMBALAJE, '' DESPACHO, NULL ARREGLO, '' PRIORIDAD,'' FECHA_DESPACHO FROM RO_PEDIDOS_PENDIENTE_MAYORISTAS 
                WHERE NRO_PEDIDO NOT IN (SELECT NRO_PEDIDO FROM RO_PEDIDOS_MAYORISTA_ASIGNADOS WHERE COD_CLIENT = '$cliente') 
                ) A
                WHERE COD_CLIENT = '$cliente' ORDER BY FECHA";

        $rows = $this->retornarArray($sql);
        
        $myJSON = json_encode($rows);

        return $myJSON;

    }

    public function traerPedidosAsignados($cliente, $desde, $hasta)
    {

        $sql = "SELECT FECHA_DESPACHO, COD_CLIENT, RAZON_SOCI, COUNT(NRO_PEDIDO) CANT_PEDIDOS, SUM(CANT_PENDIENTE) UNID_PENDIENTE, SUM(IMP_PENDIENTE) IMP_PENDIENTE FROM RO_PEDIDOS_MAYORISTA_ASIGNADOS
                WHERE RAZON_SOCI LIKE '$cliente' AND FECHA_DESPACHO BETWEEN '$desde' AND '$hasta'
                GROUP BY FECHA_DESPACHO, COD_CLIENT, RAZON_SOCI";

        $rows = $this->retornarArray($sql);
        
        $myJSON = json_encode($rows);

        return $myJSON;

    }

}