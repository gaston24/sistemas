
<?php

$matriz = $_POST['matriz'];

foreach($matriz as $id){
  require_once '../Class/Conexion.php';

    $cid = new Conexion();
    $cid_central = $cid->conectar();        

    $sql = "DELETE FROM SJ_EXCLUIDOS WHERE ID = '$id'";

    $stmt = sqlsrv_query( $cid_central, $sql );

}

echo $id;

?>