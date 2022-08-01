<?php

require_once 'Pedido.php';

$numOrd = new numero_Orden();
$proxNumOrd = $numOrd->traerNumOrden();

$fecha= Date('Y-m-d');

date_default_timezone_set('Etc/GMT+3');
$Object = new DateTime();  
$hora = $Object->format("G:i");

$matriz = $_POST['matriz'];
// var_dump($matriz);
// echo $matriz;
// die();

foreach($matriz as $key => $var){
  
  $articulo = $var[1];
  $descrip = $var[2];
  $rubro = $var[3];
  $precio = $var[4];
  $temporada = $var[5];
  $topeCant = $var[6];
  $lanzamiento = $var[7];

  var_dump($topeCant);

  require_once 'Conexion.php';

    $cid = new Conexion();
    $cid_central = $cid->conectar();        

    $sql = "
    
    INSERT INTO RO_ORDENES_PRECOMPRA (FECHA, HORA, NRO_ORDEN, COD_ARTICU, DESCRIPCIO, RUBRO, PRECIO_ESTIMADO, TEMPORADA, LANZAMIENTO, CANT_MAX)
    VALUES ('$fecha','$hora',$proxNumOrd,'$articulo','$descrip','$rubro',$precio,'$temporada','$lanzamiento','$topeCant');

    UPDATE RO_ORDENES_PRECOMPRA SET CANT_MAX = NULL WHERE CANT_MAX = '0'

    ";
    $stmt = sqlsrv_query( $cid_central, $sql );

}


?>

