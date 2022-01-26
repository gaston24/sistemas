
<?php

class Cronograma
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

    public function traerCronograma($tipo)
    {

        $sql = "SELECT * FROM RO_CRONOGRAMAS_DETALLE WHERE TIPO LIKE '$tipo' ORDER BY NRO_SUCURSAL";

        $rows = $this->retornarArray($sql);


        $myJSON = json_encode($rows);

        return $myJSON;
    }

    public function update($prioridad, $cronograma)
    {
        /*   var_dump($prioridad[0]->{'codClient'});
       var_dump($prioridad[0]->{'valuePrioridad'}); */
        if (count($prioridad) > 0 || count($cronograma) > 0) {
            
            foreach ($prioridad as $item) {
                // var_dump($item->{'codClient'});
                // var_dump($item->{'valuePrioridad'});

                $codClient = $item->{'codClient'};
                $prioridad = $item->{'valuePrioridad'};
                
                $sql = " EXEC RO_UPDATE_CRONOGRAMA_DESPACHO_PRIORIDAD '$codClient','$prioridad' ";

                $rows = $this->retornarArray($sql);

            }

            foreach ($cronograma as $item) {

                $codClient = $item->{'codClient'};
                $nameDia = $item->{'nameDia'};
                
                $sql = " EXEC RO_UPDATE_CRONOGRAMA_DESPACHO '$codClient','$nameDia' ";

                $rows = $this->retornarArray($sql);

            }
        } else {
            echo 'error';
        }
    }
}


$nuevoCrono = new Cronograma();

if (isset($_GET['prioridad']) && isset($_GET['cronograma'])) {
    $prioridad = json_decode($_GET['prioridad']);
    $cronograma = json_decode($_GET['cronograma']);
    $nuevoCrono->update($prioridad, $cronograma);
}
