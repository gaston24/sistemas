
<?php

session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{

$codClient = $_SESSION['codClient']; 

$nroOrden = $_POST['orden'];


require_once '../Class/Pedido.php';

$numPed = new Pedido();
$proxNumPed = $numPed->traerNumPed();

$fecha= Date('Y-m-d');

date_default_timezone_set('Etc/GMT+3');
$Object = new DateTime();  
$hora = $Object->format("G:i");

$matriz = $_POST['matriz'];

foreach($matriz as $key => $var){
  $articulo = $var[1];
  $descrip = $var[2];
  $rubro = $var[3];
  $precio = $var[4];
  $temporada = $var[5];
  $cantidad = $var[7];

  require_once '../Class/Conexion.php';

    $cid = new Conexion();
    $cid_central = $cid->conectar();        

    $sql = "
    INSERT INTO DBO.RO_PEDIDO_PRECOMPRA (FECHA, HORA, COD_CLIENT, NRO_NOTA_PEDIDO, COD_ARTICU, DESCRIPCIO, RUBRO, PRECIO_ESTIMADO, TEMPORADA, CANTIDAD, NRO_ORDEN) VALUES 
    ('$fecha','$hora','$codClient',$proxNumPed,'$articulo','$descrip', '$rubro', $precio,'$temporada',$cantidad,$nroOrden);

    ";
    $stmt = sqlsrv_query( $cid_central, $sql );

  }
}
?>