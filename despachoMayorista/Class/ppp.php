
<?php

class PPP
{

    private function retornarArray($sqlEnviado)
    {

        require_once 'Conexion.php';
        /* echo $sqlEnviado; */
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


    public function traerCuenta($cliente)
    {
        $sql = "SELECT * FROM RO_PPP_DETALLADO_MAYORISTAS WHERE COD_CLIENTE = '$cliente'";
        $sql=str_replace('"','',$sql);
        /* echo $sql; */
        /* $rows = $this->retornarArray($sql); */
        require_once 'Conexion.php';
        /* echo $sqlEnviado; */
        $cid = new Conexion();
        $cid_central = $cid->conectar();
        /* $sql = $sqlEnviado; */

        $stmt = sqlsrv_query($cid_central, $sql);

        $rows = array();

        while ($v = sqlsrv_fetch_array($stmt)) {
            $rows[] = $v;
        }


        $myJSON = json_encode($rows);

        print_r($myJSON);

    }

}

$p = new PPP();
if(isset($_GET['cliente']))
{
    $p->traerCuenta($_GET['cliente']);
}