
<?php

class PPP
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


    public function traerCuenta()
    {

        $sql = "SELECT * FROM RO_PPP_DETALLADO_MAYORISTAS WHERE COD_CLIENTE = 'MABUL3'";

        $rows = $this->retornarArray($sql);

        $myJSON = json_encode($rows);

        return $myJSON;

    }

}