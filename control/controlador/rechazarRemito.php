<?php 
require_once '../../class/remito.php';

$nroRemito = $_POST['nroRemito'];

$remito = new Remito();

$remito->rechazarRemito($nroRemito);

return true;

?>