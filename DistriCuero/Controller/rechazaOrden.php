<?php
session_start(); 
$fecha= Date('Y-m-d');

date_default_timezone_set('Etc/GMT+3');
$Object = new DateTime();  
$hora = $Object->format("G:i");

$codClient = $_SESSION['codClient'];

$matriz = $_POST['matriz'];


foreach($matriz as $orden){
  require_once '../Class/Conexion.php';

    $cid = new Conexion();
    $cid_central = $cid->conectar();      

    $sql = "

    INSERT INTO RO_ORDENES_RECHAZADAS (FECHA, HORA, COD_CLIENT, NRO_ORDEN)
	    VALUES ('$fecha','$hora','$codClient',$orden)

    ";
    $stmt = sqlsrv_query( $cid_central, $sql );

}

echo $orden;

?>