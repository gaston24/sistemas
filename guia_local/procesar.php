<?php


date_default_timezone_set ('America/Argentina/Buenos_Aires');


//var_dump($_POST);

$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave="Axoft1988";

$cid = odbc_connect($dsn, $usuario, $clave);

foreach($_POST['orden'] as $key =>$a){

    $orden = $_POST['orden'][$key];
    $fecha =  date("Y-m-d H:i:s");
    
    date_default_timezone_set('Etc/GMT+3');
    $Object = new DateTime();  
    $hora = $Object->format("G:i");

    echo $orden.' - '.$fecha.'<br>';

    $sql=
	"
    UPDATE SJ_LOCAL_ENTREGA_TABLE SET ENTREGADO = 1, FECHA_ENTREGADO = '$fecha', HORA_ENTREGA = '$hora' 
    WHERE NRO_ORDEN_ECOMMERCE =  '$orden' AND ENTREGADO = 0
    ";

    odbc_exec($cid,$sql) or die(exit("Error en odbc_exec"));

}

header('Location: index.php');

//974181504805-01
//974192358435-01
//974153276397-01

?>

