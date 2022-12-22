
<?php


$notaPedido = $_POST['notaPedido'];
// var_dump($area);

  require_once '../Class/conexion.php';

    $cid = new Conexion();
    $cid_central = $cid->conectar();        

    $sql = "DELETE FROM RO_PEDIDO_PRECOMPRA WHERE NRO_NOTA_PEDIDO = '$notaPedido'
    ";

    $stmt = sqlsrv_query( $cid_central, $sql );

echo $notaPedido;

?>