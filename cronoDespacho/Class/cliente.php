
<?php

class Cliente
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


    public function traerClientes()
    {

        $sql = "SELECT DISTINCT(RAZON_SOCI) RAZON_SOCI FROM RO_PEDIDOS_PENDIENTES_DESPACHO
        GROUP BY RAZON_SOCI";

        $rows = $this->retornarArray($sql);

        return $rows;
    }

    public function traerSinFecha()
    {

        $sql = "SELECT * FROM RO_CLIENTES_SIN_ASIGNAR";

        $rows = $this->retornarArray($sql);

        return $rows;
    }

}