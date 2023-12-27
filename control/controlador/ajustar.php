<?php 
require_once '../../class/remito.php';

$nroRemito = $_POST['nroRemito'];
$value = $_POST['value'];

$remito = new Remito();

$remito->actualizarAjusar($value, $nroRemito);

return true;

?>