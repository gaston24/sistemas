
<?php

class Canal
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


    public function traerCanales()
    {

        $sql = "SELECT DISTINCT(CANAL) CANAL FROM RO_PEDIDOS_PENDIENTES_DESPACHO
        GROUP BY CANAL";

        $rows = $this->retornarArray($sql);

        return $rows;
    }

}