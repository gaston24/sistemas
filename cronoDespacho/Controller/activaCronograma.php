<?php

$fecha = Date('Y-m-d');
$id = $_POST['check'];

  require_once '../Class/Conexion.php';

    $cid = new Conexion();
    $cid_central = $cid->conectar();      

    $sql = "

    EXEC SP_RO_ACTIVAR_CRONOGRAMA $id

    ";

    $stmt = sqlsrv_query( $cid_central, $sql );

    if($id == 1){
        $cronograma = 'Normal';
    } elseif ($id == 2){
        $cronograma = 'Lunes Feriado';
    } else {
        $cronograma = 'Viernes Feriado';
    };

    echo $cronograma;

?>
