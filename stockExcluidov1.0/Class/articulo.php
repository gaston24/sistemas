
<?php

class Articulo
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


    public function traerArticulos()
    {

        $sql = "SELECT * FROM SJ_EXCLUIDOS ORDER BY ID DESC";
        
        $rows = $this->retornarArray($sql);

        $myJSON = json_encode($rows);

        return $myJSON;

    }

    public function traerMaestro($codArticu){

        $sql = "SELECT A.COD_ARTICU, DESCRIPCIO, B.PRECIO, TEMPORADA FROM STA11 A
                INNER JOIN (SELECT COD_ARTICU, PRECIO FROM GVA17 WHERE NRO_DE_LIS = '20') B ON A.COD_ARTICU = B.COD_ARTICU
                LEFT JOIN MAESTRO_DESTINOS C ON A.COD_ARTICU = C.COD_ARTICU
                INNER JOIN SOF_RUBROS_TANGO D ON A.COD_ARTICU = D.COD_ARTICU
                WHERE A.COD_ARTICU LIKE 'X%'
                AND A.COD_ARTICU = '$codArticu'
        ";

        $rows = $this->retornarArray($sql);

        return $rows;

    }

}