

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

        $sql = "SELECT DISTINCT(COD_CLIENT), RAZON_SOCI FROM RO_PEDIDOS_MAYORISTA_ASIGNADOS";
        
        $rows = $this->retornarArray($sql);

        return $rows;

    }

}