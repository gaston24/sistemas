
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

        $sql = "SELECT DISTINCT(COD_CLIENT) COD_CLIENT, RAZON_SOCI FROM RO_PEDIDOS_PENDIENTES_DESPACHO
                WHERE COD_CLIENT != ''
                GROUP BY RAZON_SOCI, COD_CLIENT
                ORDER BY COD_CLIENT
                ";

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