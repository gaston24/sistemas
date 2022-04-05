
<?php

class Vendedor
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


    public function traerVendedores()
    {

        $sql = "SELECT DISTINCT(COD_VENDED), VENDEDOR FROM RO_PEDIDOS_PENDIENTE_MAYORISTAS";
        
        $rows = $this->retornarArray($sql);

        return $rows;

    }

}