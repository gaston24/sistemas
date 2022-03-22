
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


    public function traerPedidos($canal, $cliente, $desde, $hasta)
    {

        $sql = "SELECT * FROM RO_PEDIDOS_PENDIENTES_DESPACHO WHERE CANAL LIKE '$canal' AND RAZON_SOCI LIKE '$cliente' AND RE_DESPACHO
        BETWEEN '$desde' AND '$hasta' AND IMPRESO = 'NO'";

        $rows = $this->retornarArray($sql);
        
        $myJSON = json_encode($rows);

        return $myJSON;

    }

}